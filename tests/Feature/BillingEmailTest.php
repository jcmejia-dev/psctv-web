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
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;

class BillingEmailTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customer;
    protected $package;
    protected $subscription;
    protected $invoice;

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

        // Create package
        $this->package = Package::create([
            'name' => 'Test Package',
            'description' => 'Test package description',
            'price' => 100.00,
            'billing_cycle' => 'monthly',
            'free_trial_days' => 0,
            'reconnection_fee' => 50.00,
            'auto_disconnect_overdue' => true,
            'is_active' => true
        ]);

        // Create subscription
        $this->subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'package_id' => $this->package->id,
            'status' => 'active',
            'service_status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        // Create invoice
        $this->invoice = Invoice::create([
            'subscription_id' => $this->subscription->id,
            'customer_id' => $this->customer->id,
            'invoice_no' => 'INV-' . time(),
            'amount_due' => 100.00,
            'status' => 'unpaid',
            'due_date' => now()->toDateString(),
            'is_recurring' => false
        ]);
    }

    /** @test */
    public function mark_paid_sends_receipt_email_to_customer()
    {
        $this->actingAs($this->admin);

        Mail::fake();

        // Mark invoice as paid
        $response = $this->post(route('billing.markPaid', $this->invoice->id), [
            'amount' => 100.00,
            'method' => 'Cash'
        ]);

        $response->assertRedirect();

        // Assert email was sent
        Mail::assertSent(PaymentReceiptMail::class, function ($mail) {
            return $mail->hasTo('customer@test.com');
        });

        // Assert email contains correct data
        Mail::assertSent(PaymentReceiptMail::class, function ($mail) {
            return $mail->invoice->id === $this->invoice->id &&
                   $mail->payment->amount_paid == 100.00;
        });
    }

    /** @test */
    public function mark_paid_does_not_send_email_if_no_customer_email()
    {
        $this->actingAs($this->admin);

        // Remove customer email (set to empty string)
        $this->customer->user->update(['email' => '']);

        Mail::fake();

        // Mark invoice as paid
        $response = $this->post(route('billing.markPaid', $this->invoice->id), [
            'amount' => 100.00,
            'method' => 'Cash'
        ]);

        $response->assertRedirect();

        // Assert no email was sent
        Mail::assertNotSent(PaymentReceiptMail::class);
    }

    /** @test */
    public function mark_paid_does_not_send_email_for_manual_invoice_without_subscription()
    {
        $this->actingAs($this->admin);

        // Create manual invoice without subscription
        $manualInvoice = Invoice::create([
            'customer_name' => 'Manual Customer',
            'invoice_no' => 'MAN-' . time(),
            'amount_due' => 50.00,
            'status' => 'unpaid',
            'due_date' => now()->toDateString(),
            'is_recurring' => false
        ]);

        Mail::fake();

        // Mark manual invoice as paid
        $response = $this->post(route('billing.markPaid', $manualInvoice->id), [
            'amount' => 50.00,
            'method' => 'Cash'
        ]);

        $response->assertRedirect();

        // Assert no email was sent
        Mail::assertNotSent(PaymentReceiptMail::class);
    }
}
