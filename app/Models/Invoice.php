<?php
namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'subscription_id',
        'customer_id',    // NEW for manual bills
        'customer_name',   // ✅ new for manual walk-in
        'invoice_no',
        'description',
        'amount_due',
        'amount_paid',
        'due_date',
        'billing_date',
        'status',
        'is_recurring',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_no)) {
                $invoice->invoice_no = 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'due_date'     => 'date',
        'billing_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    // --- Relationships ---
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function logs()
    {
        return $this->hasMany(InvoiceLog::class);
    }

    // --- Helpers ---
    public function balanceRemaining(): float
    {
        return max(0, $this->amount_due - $this->amount_paid);
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date?->isPast();
    }

    public function markOverdue()
    {
        if ($this->isOverdue()) {
            $this->updateQuietly(['status' => 'overdue']);
        }
    }

    /**
     * Update invoice status based on payments
     */
    public function updateStatusFromPayments()
    {
        $totalPaid = $this->payments()->where('status', 'paid')->sum('amount_paid');

        if ($totalPaid >= $this->amount_due) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partial';
        } elseif ($this->due_date && now()->gt($this->due_date)) {
            $status = 'overdue';
        } else {
            $status = 'unpaid';
        }

        $this->updateQuietly([
            'amount_paid' => $totalPaid,
            'status' => $status
        ]);
    }

    /**
     * Accessor for status
     * - Prevents recursion by not updating DB here.
     */
    public function getStatusAttribute($value)
    {
        // Always trust stored statuses for paid/cancelled
        if (in_array($value, ['paid', 'cancelled'])) {
            return $value;
        }

        // Mark as overdue when due date is past and still unpaid
        if ($this->due_date && now()->gt($this->due_date) && $value === 'unpaid') {
            // Use getRawOriginal() to read the actual DB column directly
            if ($this->getRawOriginal('status') !== 'overdue') {
                $this->updateQuietly(['status' => 'overdue']);
                // Update subscription service status when invoice becomes overdue
                if ($this->subscription) {
                    $this->subscription->updateServiceStatusForOverdue();
                }
            }
            return 'overdue';
        }

        return $value;
    }

    /**
     * Get remaining amount attribute
     */
    public function getRemainingAmountAttribute()
    {
        return $this->amount_due - $this->amount_paid;
    }

    /**
     * Check if invoice is fully paid
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->status === 'paid' || $this->amount_paid >= $this->amount_due;
    }

    /**
     * Check if invoice can accept payments
     */
    public function canAcceptPayments()
    {
        return !$this->is_fully_paid && $this->status !== 'cancelled';
    }

    /**
     * Get total paid amount from payments
     */
    public function getTotalPaidFromPaymentsAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount_paid');
    }
}