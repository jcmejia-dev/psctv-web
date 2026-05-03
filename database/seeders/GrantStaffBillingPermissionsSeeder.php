<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StaffModulePermission;
use App\Models\UserStaffModulePermission;

class GrantStaffBillingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff users
        $staffUsers = User::where('role', 'staff')->get();

        if ($staffUsers->isEmpty()) {
            $this->command->info('No staff users found. Skipping permission grants.');
            return;
        }

        // Get all billing permissions
        $billingPermissions = StaffModulePermission::where('key', 'like', 'billing.%')->get();

        if ($billingPermissions->isEmpty()) {
            $this->command->error('No billing permissions found. Run StaffBillingPermissionsSeeder first.');
            return;
        }

        $grantedCount = 0;

        foreach ($staffUsers as $staff) {
            foreach ($billingPermissions as $permission) {
                UserStaffModulePermission::firstOrCreate(
                    [
                        'user_id' => $staff->id,
                        'staff_module_permission_id' => $permission->id,
                    ],
                    [
                        'granted' => true,
                    ]
                );
                $grantedCount++;
            }
        }

        $this->command->info("Granted {$grantedCount} billing permissions to {$staffUsers->count()} staff users.");
    }
}
