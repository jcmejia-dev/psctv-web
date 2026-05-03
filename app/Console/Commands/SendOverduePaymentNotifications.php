<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\OverduePaymentNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendOverduePaymentNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-overdue-payments {--test : Run in test mode without sending notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue payment notifications to staff and admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testMode = $this->option('test');
        $now = now();

        if ($testMode) {
            $this->info('🔍 Running in TEST MODE - No notifications will be sent');
        }

        $this->info('🚀 Starting overdue payment notification check...');

        // Get all overdue subscriptions (more than 3 days overdue)
        $overdueSubscriptions = Subscription::where('status', 'active')
            ->where('service_status', '!=', 'disconnected')
            ->whereHas('invoices', function ($query) use ($now) {
                $query->whereIn('status', ['unpaid', 'overdue'])
                    ->where('due_date', '<', $now->copy()->subDays(3)); // More than 3 days overdue
            })
            ->with(['customer.user', 'package', 'invoices' => function ($query) use ($now) {
                $query->whereIn('status', ['unpaid', 'overdue'])
                    ->where('due_date', '<', $now->copy()->subDays(3))
                    ->orderBy('due_date', 'asc');
            }])
            ->get();

        $this->info("📊 Found {$overdueSubscriptions->count()} subscriptions with overdue payments");

        if ($overdueSubscriptions->isEmpty()) {
            $this->info('✅ No overdue payments found');
            return Command::SUCCESS;
        }

        // Get staff and admin users to notify
        $staffUsers = User::whereIn('role', ['admin', 'staff'])
            ->where('is_active', true)
            ->get();

        $this->info("👥 Found {$staffUsers->count()} staff/admin users to notify");

        $notificationsSent = 0;
        $errors = 0;

        foreach ($overdueSubscriptions as $subscription) {
            try {
                $oldestOverdueInvoice = $subscription->invoices->first();

                if (!$oldestOverdueInvoice) {
                    continue;
                }

                $daysOverdue = $now->diffInDays($oldestOverdueInvoice->due_date);

                $this->line("🔍 Processing subscription #{$subscription->id} - {$daysOverdue} days overdue");

                // Send notification to each staff/admin user
                foreach ($staffUsers as $user) {
                    try {
                        if ($testMode) {
                            $this->line("   📧 [TEST] Would notify {$user->email} about overdue payment for {$subscription->customer->user->name}");
                        } else {
                            $user->notify(new OverduePaymentNotification($subscription, $oldestOverdueInvoice, $daysOverdue));
                            $this->line("   📧 Notified {$user->email} about overdue payment");
                            $notificationsSent++;
                        }
                    } catch (\Exception $e) {
                        $this->error("   ❌ Failed to notify {$user->email}: {$e->getMessage()}");
                        $errors++;
                    }
                }

            } catch (\Exception $e) {
                $this->error("❌ Error processing subscription {$subscription->id}: {$e->getMessage()}");
                $errors++;
                Log::error("Overdue payment notification error for subscription {$subscription->id}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('📈 Summary:');
        $this->info("   • Notifications sent: {$notificationsSent}");
        $this->info("   • Errors: {$errors}");

        if ($testMode) {
            $this->warn('⚠️ Test mode completed - no actual notifications sent');
        } else {
            $this->info('✅ Overdue payment notifications completed successfully');
        }

        return Command::SUCCESS;
    }
}
