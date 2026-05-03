<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'old_package_id',
        'new_package_id',
        'effective_date',
        'effective_option',
        'requires_payment',
        'status',
        'requested_by',
        'completed_by',
        'cancelled_by',
        'completed_at',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'requires_payment' => 'boolean',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function oldPackage()
    {
        return $this->belongsTo(Package::class, 'old_package_id');
    }

    public function newPackage()
    {
        return $this->belongsTo(Package::class, 'new_package_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRequiresPayment($query)
    {
        return $query->where('requires_payment', true);
    }

    // Methods
    public function markAsCompleted($userId = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_by' => $userId ?? auth()->id(),
            'completed_at' => now(),
        ]);
    }

    public function markAsCancelled($userId = null, $notes = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_by' => $userId ?? auth()->id(),
            'cancelled_at' => now(),
            'notes' => $notes ? $this->notes . "\nCancelled: " . $notes : $this->notes,
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isEffective()
    {
        if (!$this->isPending()) {
            return false;
        }

        return now()->gte($this->effective_date);
    }
}