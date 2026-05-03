<?php

namespace Database\Seeders;

use App\Models\BillingRule;
use App\Models\Package;
use Illuminate\Database\Seeder;

class BillingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            BillingRule::create([
                'package_id' => $package->id,
                'grace_period_days' => 3,
                'late_fee_amount' => 5.00,
                'late_fee_frequency' => 'monthly',
                'suspension_days' => 2,
                'disconnection_days' => 5,
                'suspension_fee' => 10.00,
                'reconnection_fee' => 25.00,
                'auto_apply_penalties' => true,
            ]);
        }
    }
}
