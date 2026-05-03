<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InvoiceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            // ✅ Create payment record
            $payment = $invoice->payments()->create([
                'amount_paid'  => $data['amount'] ?? $invoice->amount_due,
                'method'       => $data['method'] ?? 'Cash',
                'reference'    => $data['reference'] ?? null,
                'notes'        => $data['notes'] ?? null,
                'status'       => 'paid',
                'payment_date' => now(),
               'created_by' => Auth::id(),

            ]);

            // ✅ Update invoice status
            $invoice->update([
                'status'      => 'paid',
                'amount_paid' => $payment->amount_paid,
            ]);

            // ✅ Create invoice log (works for subscription OR manual bills)
            InvoiceLog::create([
                'subscription_id' => $invoice->subscription_id, // can be null
                'invoice_no'      => $invoice->invoice_no,
                'description'     => "Invoice marked as paid ({$payment->method})",
                'amount_due'      => $invoice->amount_due,
                'due_date'        => $invoice->due_date,
                'status'          => 'paid',

                // NEW: store customer info for manual bills
                'customer_name'   => $invoice->customer_name ?? optional(optional($invoice->subscription)->customer)->user->name ?? 'Walk-in Customer',
            ]);

            return $payment;
        });
    }
}
