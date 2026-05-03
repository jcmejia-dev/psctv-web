<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionController extends Controller
{
    // Show form to edit role permissions
    public function edit(Role $role)
    {
        $permissions = Permission::all(); // list of all permissions
        return view('admin.roles.permissions', compact('role', 'permissions'));
    }

    // Save updated permissions
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Sync role's permissions
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.permissions.edit', $role->id)
                         ->with('success', 'Permissions updated successfully.');
    }
}
