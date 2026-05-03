<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SubscriptionEditTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customer;
    protected $package;
    protected $newPackage;

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

        // Create original package
        $this->package = Package::create([
            'name' => 'Original Package',
            'description' => 'Original test package',
            'price' => 100.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 30,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        // Create new package for testing changes
        $this->newPackage = Package::create([
            'name' => 'New Package',
            'description' => 'New test package',
            'price' => 150.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 15,
            'reconnection_fee' => 75.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);
    }

    /** @test */
    public function subscription_edit_updates_basic_fields()
    {
        $this->actingAs($this->admin);

        // Create subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $newStartDate = now()->addDays(5)->toDateString();
        $newEndDate = now()->addDays(35)->toDateString();

        // Update subscription
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify changes
        $subscription->refresh();
        $this->assertEquals($newStartDate, $subscription->start_date);
        $this->assertEquals($newEndDate, $subscription->end_date);
    }

    /** @test */
    public function subscription_edit_handles_package_change_with_trial()
    {
        $this->actingAs($this->admin);

        // Create subscription with trial
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'trial_end_date' => now()->addDays(30)->toDateString(),
            'is_in_trial' => true
        ]);

        // Change to new package
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $this->newPackage->id,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify package changed and trial adjusted
        $subscription->refresh();
        $this->assertEquals($this->newPackage->id, $subscription->package_id);
        $this->assertEquals(now()->addDays(15)->toDateString(), $subscription->trial_end_date);
    }

    /** @test */
    public function subscription_edit_creates_invoice_log()
    {
        $this->actingAs($this->admin);

        // Create subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $initialLogCount = $subscription->invoiceLogs()->count();

        // Update subscription
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $this->newPackage->id,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'active'
        ]);

        $response->assertRedirect();

        // Verify invoice log was created
        $subscription->refresh();
        $this->assertEquals($initialLogCount + 1, $subscription->invoiceLogs()->count());

        $latestLog = $subscription->invoiceLogs()->latest()->first();
        $this->assertEquals('subscription_updated', $latestLog->action);
        $this->assertStringContains('Package changed from', $latestLog->notes);
    }

    /** @test */
    public function subscription_edit_validates_required_fields()
    {
        $this->actingAs($this->admin);

        // Create subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        // Try to update with invalid data
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => '', // Invalid
            'package_id' => '', // Invalid
            'start_date' => 'invalid-date',
            'end_date' => '',
            'status' => 'active'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['customer_id', 'package_id', 'start_date', 'end_date']);
    }

    /** @test */
    public function subscription_edit_handles_status_changes()
    {
        $this->actingAs($this->admin);

        // Create active subscription
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        // Change status to cancelled
        $response = $this->put(route('subscriptions.update', $subscription->id), [
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'status' => 'cancelled'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify status changed
        $subscription->refresh();
        $this->assertEquals('cancelled', $subscription->status);
        $this->assertNotNull($subscription->cancelled_at);
    }
}
