<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\BillingRule;
use App\Mail\ServiceSuspendedMail;
use App\Mail\ServiceDisconnectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ManageOverdueServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:manage-overdue {--test : Run in test mode without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically manage service status for overdue payments based on billing rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testMode = $this->option('test');
        $now = now();

        if ($testMode) {
            $this->info('🔍 Running in TEST MODE - No changes will be made');
        }

        $this->info('🚀 Starting overdue service management...');

        // Get all active subscriptions with overdue invoices
        $overdueSubscriptions = Subscription::where('status', 'active')
            ->where('service_status', '!=', 'disconnected')
            ->where('status', '!=', 'disconnected')
            ->whereDoesntHave('serviceRequests', function ($query) {
                // Exclude subscriptions that already have pending reconnection requests
                $query->where('service_type', 'Reconnection')
                    ->whereIn('status', ['pending', 'assigned', 'in-progress']);
            })
            ->whereHas('invoices', function ($query) use ($now) {
                $query->whereIn('status', ['unpaid', 'overdue'])
                    ->where('due_date', '<', $now);
            })
            ->with(['customer.user', 'package.billingRule', 'package', 'invoices' => function ($query) use ($now) {
                $query->whereIn('status', ['unpaid', 'overdue'])
                    ->where('due_date', '<', $now)
                    ->orderBy('due_date', 'asc');
            }])
            ->get();

        $this->info("📊 Found {$overdueSubscriptions->count()} subscriptions with overdue payments");

        $stats = [
            'suspended' => 0,
            'disconnected' => 0,
            'notifications_sent' => 0,
            'errors' => 0
        ];

        foreach ($overdueSubscriptions as $subscription) {
            try {
                $this->processSubscription($subscription, $now, $stats, $testMode);
            } catch (\Exception $e) {
                $this->error("❌ Error processing subscription {$subscription->id}: {$e->getMessage()}");
                $stats['errors']++;
                Log::error("Overdue service management error for subscription {$subscription->id}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('📈 Summary:');
        $this->info("   • Services suspended: {$stats['suspended']}");
        $this->info("   • Services disconnected: {$stats['disconnected']}");
        $this->info("   • Notifications sent: {$stats['notifications_sent']}");
        $this->info("   • Errors: {$stats['errors']}");

        if ($testMode) {
            $this->warn('⚠️ Test mode completed - no actual changes made');
        } else {
            $this->info('✅ Overdue service management completed successfully');
        }

        return Command::SUCCESS;
    }

    /**
     * Process a single subscription for overdue management
     */
    private function processSubscription(Subscription $subscription, Carbon $now, array &$stats, bool $testMode)
    {
        $billingRule = $subscription->package->billingRule ?? new BillingRule();
        $oldestOverdueInvoice = $subscription->invoices->first();

        if (!$oldestOverdueInvoice) {
            return; // No overdue invoices found
        }

        $daysOverdue = $now->diffInDays($oldestOverdueInvoice->due_date);

        // New logic: Disconnect after 2 days overdue, suspend after 1 day overdue
        $disconnectionDays = 2;
        $suspensionDays = 1;

        $customer = $subscription->customer;
        $user = $customer->user;

        $this->line("🔍 Processing subscription #{$subscription->id} - {$daysOverdue} days overdue");

        // Determine action based on days overdue
        if ($daysOverdue >= $disconnectionDays) {
            // Disconnect service after 2 days overdue
            $this->handleServiceDisconnection($subscription, $user, $stats, $testMode);
        } elseif ($daysOverdue >= $suspensionDays) {
            // Suspend service after 1 day overdue
            $this->handleServiceSuspension($subscription, $user, $stats, $testMode);
        } else {
            // Within grace period (0 days overdue) - no action
            $this->line("   ⏳ Within grace period - no action needed yet");
        }
    }

    /**
     * Handle service suspension
     */
    private function handleServiceSuspension(Subscription $subscription, $user, array &$stats, bool $testMode)
    {
        if ($subscription->service_status === 'suspended') {
            $this->line("   ⏸️ Service already suspended");
            return;
        }

        if ($testMode) {
            $this->line("   ⏸️ [TEST] Would suspend service for {$user->email}");
        } else {
            $subscription->update(['service_status' => 'suspended']);
            $this->line("   ⏸️ Service suspended for {$user->email}");

            // Send suspension notification
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new ServiceSuspendedMail($subscription));
                    $this->line("   📧 Suspension notification sent");
                    $stats['notifications_sent']++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to send suspension email: {$e->getMessage()}");
                }
            }

            Log::info("Service suspended for subscription {$subscription->id} due to overdue payment");
        }

        $stats['suspended']++;
    }

    /**
     * Handle service disconnection
     */
    private function handleServiceDisconnection(Subscription $subscription, $user, array &$stats, bool $testMode)
    {
        if ($subscription->status === 'disconnected') {
            $this->line("   ❌ Subscription already disconnected");
            return;
        }

        if ($testMode) {
            $this->line("   ❌ [TEST] Would disconnect subscription for {$user->email}");
        } else {
            $subscription->update([
                'status' => 'disconnected',
                'service_status' => 'disconnected',
                'disconnected_at' => now()
            ]);
            $this->line("   ❌ Subscription disconnected for {$user->email}");

            // Send disconnection notification
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new ServiceDisconnectedMail($subscription));
                    $this->line("   📧 Disconnection notification sent");
                    $stats['notifications_sent']++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to send disconnection email: {$e->getMessage()}");
                }
            }

            Log::info("Subscription disconnected for subscription {$subscription->id} due to overdue payment");
        }

        $stats['disconnected']++;
    }
}
