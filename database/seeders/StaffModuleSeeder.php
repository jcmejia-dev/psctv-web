<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffModule;
use App\Models\StaffModulePermission;

class StaffModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Dashboard',
                'key' => 'dashboard',
                'description' => 'View dashboard and system overview',
                'icon' => 'heroicon-o-home',
                'route_prefix' => 'staff.dashboard',
                'order' => 1,
            ],
            [
                'name' => 'Service Request',
                'key' => 'service_requests',
                'description' => 'Manage service requests and assignments',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'route_prefix' => 'staff.service_requests',
                'order' => 2,
            ],
            [
                'name' => 'Customer',
                'key' => 'customers',
                'description' => 'View and manage customer information',
                'icon' => 'heroicon-o-users',
                'route_prefix' => 'staff.customers',
                'order' => 3,
            ],
            [
                'name' => 'Technician',
                'key' => 'technicians',
                'description' => 'Manage technician profiles and assignments',
                'icon' => 'heroicon-o-user-group',
                'route_prefix' => 'staff.technicians',
                'order' => 4,
            ],
            [
                'name' => 'Package',
                'key' => 'packages',
                'description' => 'View available service packages',
                'icon' => 'heroicon-o-cube',
                'route_prefix' => 'staff.packages',
                'order' => 5,
            ],
            [
                'name' => 'Billing',
                'key' => 'billing',
                'description' => 'Manage billing and payments',
                'icon' => 'heroicon-o-currency-dollar',
                'route_prefix' => 'staff.billing',
                'order' => 6,
            ],
            [
                'name' => 'Subscriptions',
                'key' => 'subscriptions',
                'description' => 'Manage customer subscriptions',
                'icon' => 'heroicon-o-document-text',
                'route_prefix' => 'staff.subscriptions',
                'order' => 7,
            ],
            [
                'name' => 'Announcement',
                'key' => 'announcement',
                'description' => 'Create and manage announcements',
                'icon' => 'heroicon-o-megaphone',
                'route_prefix' => 'staff.announcements',
                'order' => 7,
            ],
            [
                'name' => 'Reports',
                'key' => 'reports',
                'description' => 'View system reports and analytics',
                'icon' => 'heroicon-o-chart-bar',
                'route_prefix' => 'staff.reports',
                'order' => 8,
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = StaffModule::firstOrCreate(
                ['key' => $moduleData['key']],
                $moduleData
            );

            // Create permissions for each module
            $actions = ['view', 'create', 'edit', 'delete'];
            foreach ($actions as $action) {
                StaffModulePermission::firstOrCreate(
                    ['key' => "{$moduleData['key']}.{$action}"],
                    [
                        'staff_module_id' => $module->id,
                        'action' => $action,
                        'description' => ucfirst($action) . " {$moduleData['name']}",
                    ]
                );
            }
        }
    }
}
