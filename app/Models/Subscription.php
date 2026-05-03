<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'trial_end_date',
        'next_billing_date',
        'status',
        'service_status',
        'cancelled_at',
        'cancelled_by',
        'notes',
        'pending_plan_id',
        'pending_plan_effective_date',
        'pending_plan_notes',
        // Add these for compatibility
        'pending_package_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'trial_end_date' => 'date',
        'next_billing_date' => 'date',
        'cancelled_at' => 'datetime',
        'pending_plan_effective_date' => 'date',
        // Remove these from casts - they are not date fields
        // 'pending_plan_id',  // This shouldn't be in casts array
        // 'pending_package_id', // This shouldn't be in casts array
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function pendingPlan()
    {
        return $this->belongsTo(Package::class, 'pending_plan_id');
    }

    // ADD THIS: Relationship for pendingPackage (alias for pendingPlan for compatibility)
    public function pendingPackage()
    {
        return $this->belongsTo(Package::class, 'pending_plan_id');
    }

    // ADD THIS: Relationship for planChangeRequests
    public function planChangeRequests()
    {
        return $this->hasMany(PlanChangeRequest::class);
    }

    // Accessors
    public function getIsInTrialAttribute()
    {
        return $this->trial_end_date && now()->lte($this->trial_end_date);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && $this->service_status === 'active';
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && now()->gt($this->end_date);
    }

    /**
     * Check if subscription has any overdue invoices
     */
    public function hasOverdueInvoices()
    {
        return $this->invoices()->where('status', 'overdue')->exists();
    }

    /**
     * Update service status based on overdue invoices
     */
    public function updateServiceStatusForOverdue()
    {
        if ($this->hasOverdueInvoices() && $this->service_status === 'active') {
            $this->update(['service_status' => 'suspended']);
        } elseif (!$this->hasOverdueInvoices() && $this->service_status === 'suspended' && $this->status === 'active') {
            $this->update(['service_status' => 'active']);
        }
    }

    // Methods
    public function generateInvoice()
    {
        if (!$this->package) {
            return null;
        }

        $invoice = $this->invoices()->create([
            'subscription_id' => $this->id,
            'customer_id'     => $this->customer_id,
            'customer_name'   => $this->customer?->user?->name ?? null,
            'amount_due'      => $this->package->price ?? 0,
            'amount_paid'     => 0,
            'billing_date'    => now(),
            'due_date'        => $this->end_date,
            'status'          => 'unpaid',
            'description'     => "Recurring billing for {$this->package->name}",
            'is_recurring'    => true,
            'notes'           => "Recurring billing for {$this->package->name}",
        ]);

        // Update next billing date after generating invoice
        $this->updateNextBillingDate();

        return $invoice;
    }

    /**
     * Update the next billing date based on package billing cycle
     */
    public function updateNextBillingDate()
    {
        if (!$this->package) {
            return;
        }

        $currentBillingDate = $this->next_billing_date ?? now();

        $nextBillingDate = match (strtolower($this->package->billing_cycle ?? 'monthly')) {
            'yearly' => $currentBillingDate->copy()->addYear(),
            'quarterly' => $currentBillingDate->copy()->addMonths(3),
            'weekly' => $currentBillingDate->copy()->addWeek(),
            default => $currentBillingDate->copy()->addMonth(),
        };

        $this->update(['next_billing_date' => $nextBillingDate]);
    }

    /**
     * Calculate prorated amount for package change
     */
    public function calculateProratedAmount($newPackage, $changeDate)
    {
        if (!$this->package) {
            return 0;
        }

        $currentBillingDate = $this->next_billing_date ?? now();
        $daysRemaining = $currentBillingDate->diffInDays($changeDate, false);

        if ($daysRemaining <= 0) {
            return 0;
        }

        $priceDifference = $newPackage->price - $this->package->price;
        $totalDaysInCycle = $this->getDaysInBillingCycle($currentBillingDate);

        return round(($priceDifference * $daysRemaining) / $totalDaysInCycle, 2);
    }

    /**
     * Get number of days in current billing cycle
     */
    private function getDaysInBillingCycle($billingDate)
    {
        return match (strtolower($this->package->billing_cycle ?? 'monthly')) {
            'yearly' => 365,
            'quarterly' => 90,
            'weekly' => 7,
            default => 30, // monthly
        };
    }

    /**
     * Change package with proration
     */
    public function changePackage($newPackage, $changeDate = null)
    {
        $changeDate = $changeDate ?? now();

        // Calculate proration if changing mid-cycle
        $proratedAmount = $this->calculateProratedAmount($newPackage, $changeDate);

        if ($proratedAmount != 0) {
            // Create prorated invoice
            $this->invoices()->create([
                'subscription_id' => $this->id,
                'customer_id'     => $this->customer_id,
                'customer_name'   => $this->customer?->user?->name ?? null,
                'amount_due'      => $proratedAmount,
                'amount_paid'     => 0,
                'billing_date'    => $changeDate,
                'due_date'        => $this->end_date,
                'status'          => 'unpaid',
                'description'     => "Prorated charge for package change to {$newPackage->name}",
                'is_recurring'    => false,
                'notes'           => "Package change from {$this->package->name} to {$newPackage->name}",
            ]);
        }

        // Update subscription
        $this->update([
            'package_id' => $newPackage->id,
            'next_billing_date' => $this->calculateNextBillingDate($changeDate, $newPackage),
        ]);

        return $proratedAmount;
    }

    /**
     * Calculate next billing date after package change
     */
    private function calculateNextBillingDate($changeDate, $newPackage)
    {
        return match (strtolower($newPackage->billing_cycle ?? 'monthly')) {
            'yearly' => $changeDate->copy()->addYear(),
            'quarterly' => $changeDate->copy()->addMonths(3),
            'weekly' => $changeDate->copy()->addWeek(),
            default => $changeDate->copy()->addMonth(),
        };
    }

    /**
     * Set a pending plan change
     */
    public function setPendingPlan($newPackageId, $effectiveDate = null, $notes = null)
    {
        if ($effectiveDate === null) {
            // Find the current unpaid invoice
            $currentInvoice = $this->invoices()
                ->where('status', '!=', 'paid')
                ->where('status', '!=', 'cancelled')
                ->orderBy('billing_date', 'desc')
                ->first();

            if ($currentInvoice) {
                // Set effective date to next billing date after current invoice payment
                $effectiveDate = $this->next_billing_date ?? now()->addMonth();
            } else {
                // No unpaid invoices - customer is eligible for immediate plan change
                $effectiveDate = now();
            }
        }

        return $this->update([
            'pending_plan_id' => $newPackageId,
            'pending_plan_effective_date' => $effectiveDate,
            'pending_plan_notes' => $notes,
        ]);
    }

    /**
     * Cancel pending plan change
     */
    public function cancelPendingPlan()
    {
        return $this->update([
            'pending_plan_id' => null,
            'pending_plan_effective_date' => null,
            'pending_plan_notes' => null,
        ]);
    }

    /**
     * Check if subscription has a pending plan change
     */
    public function hasPendingPlan()
    {
        return !is_null($this->pending_plan_id);
    }

    /**
     * Apply pending plan change if effective date has passed and current invoice is paid
     */
    public function applyPendingPlanIfDue()
    {
        if (!$this->hasPendingPlan()) {
            return false;
        }

        // Check if current invoice is paid before applying pending plan
        $currentInvoice = $this->invoices()
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->orderBy('billing_date', 'desc')
            ->first();

        // If there's an unpaid invoice, don't apply the pending plan yet
        if ($currentInvoice) {
            return false;
        }

        if (now()->gte($this->pending_plan_effective_date)) {
            $this->applyPendingPlan();
            return true;
        }

        return false;
    }

    /**
     * Apply the pending plan change immediately
     */
    public function applyPendingPlan()
    {
        if (!$this->hasPendingPlan()) {
            return false;
        }

        // Calculate proration for the plan change
        $proratedAmount = $this->calculateProratedAmount($this->pendingPlan, $this->pending_plan_effective_date);

        if ($proratedAmount != 0) {
            // Create prorated invoice
            $this->invoices()->create([
                'subscription_id' => $this->id,
                'customer_id'     => $this->customer_id,
                'customer_name'   => $this->customer?->user?->name ?? null,
                'amount_due'      => $proratedAmount,
                'amount_paid'     => 0,
                'billing_date'    => $this->pending_plan_effective_date,
                'due_date'        => $this->end_date,
                'status'          => 'unpaid',
                'description'     => "Prorated charge for scheduled plan change to {$this->pendingPlan->name}",
                'is_recurring'    => false,
                'notes'           => $this->pending_plan_notes ?? "Scheduled plan change from {$this->package->name} to {$this->pendingPlan->name}",
            ]);
        }

        // Update subscription with new plan
        $this->update([
            'package_id' => $this->pending_plan_id,
            'next_billing_date' => $this->calculateNextBillingDate($this->pending_plan_effective_date, $this->pendingPlan),
        ]);

        // Clear pending plan
        $this->cancelPendingPlan();

        return true;
    }

    /**
     * Get pending plan change details
     */
    public function getPendingPlanDetails()
    {
        if (!$this->hasPendingPlan()) {
            return null;
        }

        return [
            'pending_plan' => $this->pendingPlan,
            'effective_date' => $this->pending_plan_effective_date,
            'notes' => $this->pending_plan_notes,
            'days_until_effective' => now()->diffInDays($this->pending_plan_effective_date, false),
        ];
    }

    /**
     * ADDED: Accessor for pending_package_id (for compatibility)
     */
    public function getPendingPackageIdAttribute()
    {
        return $this->pending_plan_id;
    }

    /**
     * ADDED: Mutator for pending_package_id (for compatibility)
     */
    public function setPendingPackageIdAttribute($value)
    {
        $this->attributes['pending_plan_id'] = $value;
    }
}