<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StaffModule;
use App\Models\UserStaffModuleAccess;

class StaffModuleAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff users
        $staffUsers = User::where('role', 'staff')->get();

        // Get the modules to grant access to
        $modulesToGrant = ['customers', 'packages', 'announcements','billing', 'technicians', 'service_requests', 'dashboard', 'reports', ];

        foreach ($modulesToGrant as $moduleKey) {
            $module = StaffModule::where('key', $moduleKey)->first();

            if ($module && $staffUsers->count() > 0) {
                foreach ($staffUsers as $staff) {
                    UserStaffModuleAccess::firstOrCreate(
                        [
                            'user_id' => $staff->id,
                            'staff_module_id' => $module->id,
                        ],
                        [
                            'has_access' => true,
                        ]
                    );
                }
            }
        }
    }
}
