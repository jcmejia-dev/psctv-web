<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::create([
                'subscription_id' => $data['subscription_id'] ?? null,
                'description'     => $data['description'] ?? 'Billing Charge',
                'amount_due'      => $data['amount_due'],
                'due_date'        => $data['due_date'] ?? Carbon::now()->addDays(7),
                'billing_date'    => now(),
                'status'          => 'unpaid',
                'notes'           => $data['notes'] ?? null,
            ]);

            InvoiceLog::create([
                'subscription_id' => $invoice->subscription_id,
                'invoice_no'      => $invoice->invoice_no,
                'description'     => "Invoice created: {$invoice->description}",
                'amount_due'      => $invoice->amount_due,
                'due_date'        => $invoice->due_date,
                'status'          => $invoice->status,
            ]);

            return $invoice;
        });
    }

    public function cancelInvoice(Invoice $invoice): bool
    {
        return DB::transaction(function () use ($invoice) {
            $invoice->update(['status' => 'cancelled']);

            InvoiceLog::create([
                'subscription_id' => $invoice->subscription_id,
                'invoice_no'      => $invoice->invoice_no,
                'description'     => "Invoice cancelled",
                'amount_due'      => $invoice->amount_due,
                'due_date'        => $invoice->due_date,
                'status'          => $invoice->status,
            ]);

            return true;
        });
    }
}
