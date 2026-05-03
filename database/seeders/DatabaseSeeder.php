<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BillingRuleSeeder::class,
            OverdueBillingSeeder::class,
            UserSeeder::class,
            StaffModuleAccessSeeder::class,
            StaffModuleAccessSeeder::class,
            GrantStaffBillingPermissionsSeeder::class,
            SubscriptionSeeder::class,
            ReportsDemoSeeder::class,

        ]);
    }
}
