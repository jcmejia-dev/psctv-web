<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\InvoiceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateInvoices extends Command
{
    protected $signature = 'invoices:generate {--test : Run in test mode (no database changes)}';
    protected $description = 'Generate recurring invoices for active subscriptions';

    public function handle()
    {
        $testMode = $this->option('test');
        $this->info($testMode ? '🧪 Running invoice generator in TEST mode...' : '🔄 Generating invoices...');

        $subscriptions = Subscription::with('package')
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', today());
            })
            ->get();

        $count = 0;

        foreach ($subscriptions as $sub) {
            $alreadyBilled = $sub->invoices()
                ->whereDate('created_at', today())
                ->exists();

            if ($alreadyBilled) {
                continue;
            }

            if ($testMode) {
                $this->line("🧾 [TEST] Would generate invoice for Subscription {$sub->id} ({$sub->package->name})");
                $count++;
                continue;
            }

            DB::transaction(function () use ($sub, &$count) {
                $cycle = $sub->package->billing_cycle ?? 'monthly';

                $nextBilling = match ($cycle) {
                    'yearly'     => now()->copy()->addYear(),
                    'quarterly'  => now()->copy()->addMonths(3),
                    default      => now()->copy()->addMonth(),
                };

                $invoice = $sub->invoices()->create([
                    'amount_due'   => $sub->package->price ?? 0,
                    'billing_date' => now()->toDateString(),
                    'due_date'     => $nextBilling->copy()->subDays(3)->toDateString(),
                    'description'  => "Recurring Billing for {$sub->package->name}",
                    'status'       => 'unpaid',
                    'is_recurring' => true,
                ]);

                $sub->update(['next_billing_date' => $nextBilling->toDateString()]);

                InvoiceLog::create([
                    'subscription_id' => $sub->id,
                    'invoice_id'      => $invoice->id,
                    'message'         => "Invoice {$invoice->invoice_no} generated automatically.",
                ]);

                Log::info("✅ Invoice {$invoice->invoice_no} generated for Subscription {$sub->id}");

                $count++;
            });
        }

        $summaryMsg = $testMode
            ? "🧪 TEST completed: {$count} invoices would have been created."
            : "📌 Finished generating invoices: {$count} created at " . now();

        $this->info($summaryMsg);
        Log::info($summaryMsg);
    }
}
