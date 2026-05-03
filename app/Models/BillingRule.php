<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'grace_period_days',
        'late_fee_amount',
        'late_fee_frequency',
        'suspension_days',
        'disconnection_days',
        'suspension_fee',
        'reconnection_fee',
        'auto_apply_penalties',
    ];

    protected $casts = [
        'grace_period_days' => 'integer',
        'late_fee_amount' => 'decimal:2',
        'suspension_days' => 'integer',
        'disconnection_days' => 'integer',
        'suspension_fee' => 'decimal:2',
        'reconnection_fee' => 'decimal:2',
        'auto_apply_penalties' => 'boolean',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Ensure default billing rules exist for all active packages
    public static function ensureDefaultsExist()
    {
        $packages = Package::where('is_active', true)->get();

        foreach ($packages as $package) {
            if (!self::where('package_id', $package->id)->exists()) {
                self::create([
                    'package_id' => $package->id,
                    'grace_period_days' => 3,
                    'late_fee_amount' => 5.00,
                    'late_fee_frequency' => 'monthly',
                    'suspension_days' => 2,
                    'disconnection_days' => 3,
                    'suspension_fee' => null,
                    'reconnection_fee' => null,
                    'auto_apply_penalties' => true,
                ]);
            }
        }
    }

    // Accessors
    public function getLateFeeFrequencyAttribute($value)
    {
        return $value ?? 'monthly';
    }

    public function getGracePeriodDaysAttribute($value)
    {
        return $value ?? 3;
    }

    public function getLateFeeAmountAttribute($value)
    {
        return $value ?? 5.00;
    }

    public function getSuspensionDaysAttribute($value)
    {
        return $value ?? 2;
    }
    
    public function getDisconnectionDaysAttribute($value)
    {
        return $value ?? 3;
    }

    public function getAutoApplyPenaltiesAttribute($value)
    {
        return $value ?? true;
    }
}
