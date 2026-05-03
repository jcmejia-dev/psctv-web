<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffModule;
use App\Models\UserStaffModuleAccess;
use App\Models\UserStaffModulePermission;
use Illuminate\Http\Request;

class StaffModuleAccessController extends Controller
{
    /**
     * Show module access management for a staff member
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'staff') {
            return back()->withErrors(['error' => 'This user is not a staff member.']);
        }

        $modules = StaffModule::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get current access for this user
        $userAccess = $user->staffModuleAccess()
            ->pluck('has_access', 'staff_module_id')
            ->toArray();

        // Get billing permissions for this user
        $billingModule = StaffModule::where('key', 'billing')->first();
        $billingPermissions = [];
        $userBillingPermissions = [];

        if ($billingModule) {
            $billingPermissions = $billingModule->permissions()->orderBy('action')->get();
            $userBillingPermissions = $user->staffModulePermissions()
                ->whereHas('permission', function ($q) use ($billingModule) {
                    $q->where('staff_module_id', $billingModule->id);
                })
                ->pluck('granted', 'staff_module_permission_id')
                ->toArray();
        }

        return view('admin.staff-module-access.edit', compact(
            'user',
            'modules',
            'userAccess',
            'billingPermissions',
            'userBillingPermissions'
        ));
    }

    /**
     * Update module access for a staff member
     */
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'staff') {
            return back()->withErrors(['error' => 'This user is not a staff member.']);
        }

        // Get selected modules from form
        $moduleAccess = $request->input('module_access', []);

        // Update module access for all modules
        foreach (StaffModule::all() as $module) {
            $hasAccess = in_array($module->id, $moduleAccess);

            UserStaffModuleAccess::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'staff_module_id' => $module->id,
                ],
                ['has_access' => $hasAccess]
            );
        }

        // Update billing permissions if billing module is enabled
        $billingModule = StaffModule::where('key', 'billing')->first();
        if ($billingModule && in_array($billingModule->id, $moduleAccess)) {
            $billingPermissions = $request->input('billing_permissions', []);

            // Update billing permissions for all billing actions
            foreach ($billingModule->permissions as $permission) {
                $hasPermission = in_array($permission->id, $billingPermissions);

                UserStaffModulePermission::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'staff_module_permission_id' => $permission->id,
                    ],
                    ['granted' => $hasPermission]
                );
            }
        } else {
            // If billing module is disabled, revoke all billing permissions
            if ($billingModule) {
                UserStaffModulePermission::where('user_id', $user->id)
                    ->whereHas('permission', function ($q) use ($billingModule) {
                        $q->where('staff_module_id', $billingModule->id);
                    })
                    ->update(['granted' => false]);
            }
        }

        return back()->with('success', 'Module access updated successfully!');
    }

    /**
     * Grant all modules to a staff member
     */
    public function grantAll($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'staff') {
            return back()->withErrors(['error' => 'This user is not a staff member.']);
        }

        foreach (StaffModule::all() as $module) {
            UserStaffModuleAccess::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'staff_module_id' => $module->id,
                ],
                ['has_access' => true]
            );
        }

        return back()->with('success', 'All modules enabled for this staff member!');
    }

    /**
     * Revoke all modules from a staff member
     */
    public function revokeAll($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'staff') {
            return back()->withErrors(['error' => 'This user is not a staff member.']);
        }

        foreach (StaffModule::all() as $module) {
            UserStaffModuleAccess::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'staff_module_id' => $module->id,
                ],
                ['has_access' => false]
            );
        }

        return back()->with('success', 'All modules disabled for this staff member!');
    }
}
