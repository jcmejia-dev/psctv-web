<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarkOverdueInvoices extends Command
{
    protected $signature = 'invoices:mark-overdue';
    protected $description = 'Mark unpaid invoices as overdue when past their due date';

    public function handle()
    {
        $today = Carbon::today();

        $count = Invoice::where('status', 'unpaid')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'overdue']);

        if ($count > 0) {
            $this->info("⚠️  {$count} invoices marked as overdue.");
            Log::info("⚠️  {$count} invoices marked as overdue automatically.");
        } else {
            $this->info("✅ No invoices to mark as overdue today.");
        }
    }
}
