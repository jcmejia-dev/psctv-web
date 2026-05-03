<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use App\Mail\PaymentDueReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendPaymentDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:send-due-reminders {--days=7 : Days before due date to send reminders} {--test : Run in test mode without sending emails} {--email= : Send test email to specific address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment due reminders to customers, staff, and admin for upcoming payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeDue = (int) $this->option('days');
        $testMode = $this->option('test');
        $testEmail = $this->option('email');
        $now = now();

        if ($testMode) {
            $this->info('🔍 Running in TEST MODE - No emails will be sent');
        }

        if ($testEmail) {
            $this->info("📧 Sending test email to: {$testEmail}");
            $this->sendTestEmail($testEmail);
            return Command::SUCCESS;
        }

        $this->info("🚀 Starting payment due reminder process for payments due in {$daysBeforeDue} days or less...");

        // Get subscriptions that are due within the specified days
        $dueSubscriptions = Subscription::where('status', 'active')
            ->where('service_status', '!=', 'disconnected')
            ->whereHas('customer.user')
            ->whereHas('package')
            ->where(function ($query) use ($now, $daysBeforeDue) {
                $query->where('end_date', '>=', $now)
                    ->where('end_date', '<=', $now->copy()->addDays($daysBeforeDue));
            })
            ->with(['customer.user', 'package'])
            ->get();

        $this->info("📊 Found {$dueSubscriptions->count()} subscriptions with payments due within {$daysBeforeDue} days");

        // Get admin and staff users for notifications
        $adminUsers = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->get();

        $staffUsers = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['admin', 'staff']);
        })->get();

        $this->info("👥 Found {$adminUsers->count()} admin users and {$staffUsers->count()} staff users to notify");

        $stats = [
            'customer_emails' => 0,
            'staff_emails' => 0,
            'admin_emails' => 0,
            'errors' => 0
        ];

        foreach ($dueSubscriptions as $subscription) {
            try {
                $daysUntilDue = $now->diffInDays($subscription->end_date, false);

                // Send reminder to customer
                $this->sendCustomerReminder($subscription, $daysUntilDue, $stats, $testMode);

                // Send notification to staff and admin
                $this->sendStaffNotification($subscription, $daysUntilDue, $staffUsers, $stats, $testMode);
                $this->sendAdminNotification($subscription, $daysUntilDue, $adminUsers, $stats, $testMode);

            } catch (\Exception $e) {
                $this->error("❌ Error processing subscription {$subscription->id}: {$e->getMessage()}");
                $stats['errors']++;
                Log::error("Payment due reminder error for subscription {$subscription->id}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('📈 Summary:');
        $this->info("   • Customer emails sent: {$stats['customer_emails']}");
        $this->info("   • Staff emails sent: {$stats['staff_emails']}");
        $this->info("   • Admin emails sent: {$stats['admin_emails']}");
        $this->info("   • Errors: {$stats['errors']}");

        if ($testMode) {
            $this->warn('⚠️ Test mode completed - no actual emails sent');
        } else {
            $this->info('✅ Payment due reminder process completed successfully');
        }

        return Command::SUCCESS;
    }

    /**
     * Send reminder email to customer
     */
    private function sendCustomerReminder(Subscription $subscription, $daysUntilDue, array &$stats, bool $testMode)
    {
        $customer = $subscription->customer;
        $user = $customer->user;

        if (!$user || !$user->email) {
            $this->line("   ⚠️ No email found for customer {$customer->account_number}");
            return;
        }

        if ($testMode) {
            $this->line("   📧 [TEST] Would send reminder to customer {$user->email} ({$daysUntilDue} days until due)");
        } else {
            try {
                Mail::to($user->email)->send(new PaymentDueReminderMail($subscription, $daysUntilDue));
                $this->line("   📧 Reminder sent to customer {$user->email}");
                $stats['customer_emails']++;
            } catch (\Exception $e) {
                $this->error("   ❌ Failed to send customer email: {$e->getMessage()}");
                $stats['errors']++;
            }
        }
    }

    /**
     * Send notification to staff members
     */
    private function sendStaffNotification(Subscription $subscription, $daysUntilDue, $staffUsers, array &$stats, bool $testMode)
    {
        $customer = $subscription->customer;

        foreach ($staffUsers as $staff) {
            if (!$staff->email) continue;

            if ($testMode) {
                $this->line("   👷 [TEST] Would notify staff {$staff->email} about customer {$customer->account_number}");
            } else {
                try {
                    // Create a staff-specific notification email (you might want to create a separate mail class for this)
                    Mail::to($staff->email)->send(new PaymentDueReminderMail($subscription, $daysUntilDue));
                    $stats['staff_emails']++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to send staff notification: {$e->getMessage()}");
                    $stats['errors']++;
                }
            }
        }
    }

    /**
     * Send notification to admin users
     */
    private function sendAdminNotification(Subscription $subscription, $daysUntilDue, $adminUsers, array &$stats, bool $testMode)
    {
        $customer = $subscription->customer;

        foreach ($adminUsers as $admin) {
            if (!$admin->email) continue;

            if ($testMode) {
                $this->line("   👑 [TEST] Would notify admin {$admin->email} about customer {$customer->account_number}");
            } else {
                try {
                    // Create an admin-specific notification email (you might want to create a separate mail class for this)
                    Mail::to($admin->email)->send(new PaymentDueReminderMail($subscription, $daysUntilDue));
                    $stats['admin_emails']++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to send admin notification: {$e->getMessage()}");
                    $stats['errors']++;
                }
            }
        }
    }

    /**
     * Send test email to specific address
     */
    private function sendTestEmail(string $email)
    {
        try {
            // Create a dummy subscription for testing
            $subscription = new Subscription();
            $subscription->id = 1;
            $subscription->end_date = now()->addDays(3);
            $subscription->customer = new \stdClass();
            $subscription->customer->account_number = 'TEST001';
            $subscription->customer->user = new \stdClass();
            $subscription->customer->user->name = 'Test Customer';
            $subscription->package = new \stdClass();
            $subscription->package->name = 'Test Package';
            $subscription->package->price = 100.00;

            Mail::to($email)->send(new PaymentDueReminderMail($subscription, 3));
            $this->info("✅ Test email sent successfully to {$email}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send test email: {$e->getMessage()}");
        }
    }
}
