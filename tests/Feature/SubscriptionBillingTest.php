<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SubscriptionBillingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customer;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Create customer user
        $customerUser = User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'email_verified_at' => now()
        ]);

        // Create customer
        $this->customer = Customer::create([
            'user_id' => $customerUser->id,
            'account_number' => 'ACC-' . time(),
            'phone' => '1234567890',
            'address' => '123 Test Street'
        ]);

        // Create package with free trial
        $this->package = Package::create([
            'name' => 'Test Package',
            'description' => 'Test package description',
            'price' => 100.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 30,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);
    }

    /** @test */
    public function subscription_creation_with_free_trial_skips_initial_invoice()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('subscriptions.store'), [
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'start_date' => now()->toDateString(),
            'status' => 'active'
        ]);

        $response->assertRedirect();

        $subscription = Subscription::latest()->first();
        $this->assertNotNull($subscription);
        $this->assertTrue($subscription->is_in_trial);
        $this->assertEquals('active', $subscription->service_status);
        $this->assertNotNull($subscription->trial_end_date);

        // Should not create invoice during trial
        $this->assertEquals(0, $subscription->invoices()->count());
    }

    /** @test */
    public function pending_subscription_activates_when_invoice_is_paid()
    {
        $this->actingAs($this->admin);

        // Create subscription without trial
        $packageNoTrial = Package::create([
            'name' => 'No Trial Package',
            'description' => 'Package without free trial',
            'price' => 100.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 0,
            'reconnection_fee' => 25.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        $response = $this->post(route('subscriptions.store'), [
            'customer_id' => $this->customer->id,
            'package_id' => $packageNoTrial->id,
            'start_date' => now()->toDateString(),
            'status' => 'pending'
        ]);

        $response->assertRedirect();

        $subscription = Subscription::latest()->first();
        $this->assertEquals('pending', $subscription->status);

        $invoice = $subscription->invoices()->first();
        $this->assertNotNull($invoice);
        $this->assertEquals('unpaid', $invoice->status);

        // Pay the invoice
        $response = $this->post(route('billing.markPaid', $invoice->id), [
            'amount' => $invoice->amount_due,
            'method' => 'Cash'
        ]);

        $response->assertRedirect();

        // Refresh subscription and check status
        $subscription->refresh();
        $this->assertEquals('active', $subscription->status);
        $this->assertEquals('active', $subscription->service_status);
    }

    /** @test */
    public function disconnected_service_creates_reconnection_request_when_payment_made()
    {
        $this->actingAs($this->admin);

        // Create active subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'disconnected',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'trial_end_date' => now()->addDays(30)->toDateString()
        ]);

        // Create overdue invoice
        $invoice = Invoice::create([
            'subscription_id' => $subscription->id,
            'customer_id' => $this->customer->id,
            'invoice_no' => 'INV-' . time(),
            'amount_due' => 100.00,
            'status' => 'overdue',
            'due_date' => now()->subDays(5)->toDateString(),
            'is_recurring' => false
        ]);

        // Pay the invoice
        $response = $this->post(route('billing.markPaid', $invoice->id), [
            'amount' => 100.00,
            'method' => 'Cash'
        ]);

        $response->assertRedirect();

        // Check that service remains disconnected (no automatic reactivation)
        $subscription->refresh();
        $this->assertEquals('disconnected', $subscription->service_status);

        // Check that a reconnection service request was created
        $this->assertDatabaseHas('service_requests', [
            'customer_id' => $this->customer->id,
            'subscription_id' => $subscription->id,
            'service_type' => 'Reconnection',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function cannot_delete_paid_invoice()
    {
        $this->actingAs($this->admin);

        $invoice = Invoice::create([
            'invoice_no' => 'INV-' . time(),
            'amount_due' => 100.00,
            'status' => 'paid',
            'due_date' => now()->toDateString(),
            'is_recurring' => false
        ]);

        $response = $this->delete(route('billing.destroy', $invoice->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete paid bill #'.$invoice->invoice_no.'.');

        // Invoice should still exist
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    /** @test */
    public function cannot_delete_recurring_invoice()
    {
        $this->actingAs($this->admin);

        $invoice = Invoice::create([
            'invoice_no' => 'INV-' . time(),
            'amount_due' => 100.00,
            'status' => 'unpaid',
            'due_date' => now()->toDateString(),
            'is_recurring' => true
        ]);

        $response = $this->delete(route('billing.destroy', $invoice->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete recurring bill #'.$invoice->invoice_no.'. Cancel the subscription instead.');

        // Invoice should still exist
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    /** @test */
    public function subscription_renewal_creates_new_invoice()
    {
        $this->actingAs($this->admin);

        // Create expired subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'expired',
            'service_status' => 'active',
            'start_date' => now()->subMonths(2)->toDateString(),
            'end_date' => now()->subDays(1)->toDateString(),
            'trial_end_date' => now()->subMonths(2)->addDays(30)->toDateString()
        ]);

        $response = $this->post(route('subscriptions.renew', $subscription->id));

        $response->assertRedirect();

        // Check subscription renewed
        $subscription->refresh();
        $this->assertEquals('active', $subscription->status);
        $this->assertGreaterThan(now()->toDateString(), $subscription->end_date);

        // Check new invoice created
        $invoices = $subscription->invoices;
        $this->assertGreaterThan(0, $invoices->count());
    }

    /** @test */
    public function trial_ends_and_creates_invoice()
    {
        $this->actingAs($this->admin);

        // Create subscription with trial
        $trialEnd = now()->subDays(1); // Trial already ended

        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'trial_end_date' => $trialEnd->toDateString(),
            'start_date' => $trialEnd->copy()->subDays(30)->toDateString(),
            'end_date' => $trialEnd->copy()->addMonths(1)->toDateString()
        ]);

        // Manually trigger invoice generation (this would normally be done by a scheduled job)
        $subscription->generateInvoice();

        // Should create invoice after trial ends
        $invoices = $subscription->invoices;
        $this->assertGreaterThan(0, $invoices->count());
        $this->assertEquals($this->package->price, $invoices->first()->amount_due);
    }

    /** @test */
    public function cannot_change_package_during_active_billing_cycle()
    {
        $this->actingAs($this->admin);

        // Create a new package for changing to
        $newPackage = Package::create([
            'name' => 'New Test Package',
            'description' => 'New test package description',
            'price' => 150.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 0,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        // Create subscription that started in the past (billing cycle active)
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->subDays(10)->toDateString(), // Started 10 days ago
            'end_date' => now()->addDays(20)->toDateString(),
        ]);

        // Create an invoice for this subscription
        Invoice::create([
            'subscription_id' => $subscription->id,
            'customer_id' => $this->customer->id,
            'invoice_no' => 'INV-' . time(),
            'amount_due' => 100.00,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7)->toDateString(),
            'is_recurring' => true
        ]);

        // Attempt to change package
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $newPackage->id, // Different package
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['package_id' => 'Cannot change package during an active billing cycle. Please wait until the current billing cycle ends before changing the package.']);

        // Subscription should not be updated
        $subscription->refresh();
        $this->assertEquals($this->package->id, $subscription->package_id);
    }

    /** @test */
    public function can_change_package_before_billing_cycle_starts()
    {
        $this->actingAs($this->admin);

        // Create a new package for changing to
        $newPackage = Package::create([
            'name' => 'New Test Package',
            'description' => 'New test package description',
            'price' => 150.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 0,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        // Create subscription that starts in the future (billing cycle not started)
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->addDays(5)->toDateString(), // Starts in 5 days
            'end_date' => now()->addDays(35)->toDateString(),
        ]);

        // Attempt to change package
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $newPackage->id, // Different package
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Subscription should be updated
        $subscription->refresh();
        $this->assertEquals($newPackage->id, $subscription->package_id);
    }

    /** @test */
    public function can_change_package_when_no_invoices_exist()
    {
        $this->actingAs($this->admin);

        // Create a new package for changing to
        $newPackage = Package::create([
            'name' => 'New Test Package',
            'description' => 'New test package description',
            'price' => 150.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 0,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        // Create subscription that started in the past but has no invoices
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->subDays(10)->toDateString(), // Started 10 days ago
            'end_date' => now()->addDays(20)->toDateString(),
        ]);

        // No invoices created for this subscription

        // Attempt to change package
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $newPackage->id, // Different package
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Subscription should be updated
        $subscription->refresh();
        $this->assertEquals($newPackage->id, $subscription->package_id);
    }
}
