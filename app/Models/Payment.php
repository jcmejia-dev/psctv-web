<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'payment_date',
        'method',
        'reference',
        'status',     // NEW
        'payload',    // NEW JSON data
        'created_by', // NEW (who processed)
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'payload'      => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($payment) {
            // Update invoice when payment is created
            $payment->updateInvoiceStatus();
        });

        static::updated(function ($payment) {
            // Update invoice when payment is updated
            $payment->updateInvoiceStatus();
        });

        static::deleted(function ($payment) {
            $invoice = $payment->invoice;
            if ($invoice) {
                // If no more payments exist, revert invoice back to unpaid
                if ($invoice->payments()->count() === 0) {
                    $invoice->update([
                        'status'      => 'unpaid',
                        'amount_paid' => 0,
                    ]);
                } else {
                    // If partial payments exist, recalc
                    $totalPaid = $invoice->payments()->sum('amount_paid');
                    $invoice->update([
                        'amount_paid' => $totalPaid,
                        'status'      => $totalPaid >= $invoice->amount_due ? 'paid' : 'unpaid',
                    ]);
                }
            }
        });
    }

    public function updateInvoiceStatus()
    {
        $invoice = $this->invoice;
        if ($invoice) {
            $totalPaid = $invoice->payments()
                ->where('status', 'completed')
                ->sum('amount_paid');

            $newStatus = 'unpaid';
            if ($totalPaid >= $invoice->amount_due) {
                $newStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $newStatus = 'partial';
            }

            $invoice->update([
                'amount_paid' => $totalPaid,
                'status' => $newStatus,
            ]);
        }
    }

    // --- Relationships ---
    public function serviceRequest()
    {
        return $this->belongsTo(\App\Models\ServiceRequest::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}