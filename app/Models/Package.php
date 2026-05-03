<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'speed_mbps',
        'channels',
        'price',
        'billing_cycle',
        'is_active',
        'free_trial_days',
        'reconnection_fee',
        'auto_disconnect_overdue'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'reconnection_fee' => 'decimal:2',
        'auto_disconnect_overdue' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($package) {
            // Generate unique code if not provided
            if (empty($package->code)) {
                $lastId = self::max('id') + 1;
                $prefix = strtoupper(substr($package->billing_cycle, 0, 3)); // e.g. MON, YEA
                $package->code = $prefix . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function billingRule()
    {
        return $this->hasOne(BillingRule::class);
    }
}
