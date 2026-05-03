# Staff Module Access Control System - Setup Guide

## Overview
This system allows admins to dynamically customize which modules each staff member can access. When a module is disabled for a staff member, it automatically disappears from their sidebar and page access is blocked.

## Files Created

### 1. Database Migration
**File:** `database/migrations/2025_11_25_create_staff_module_access_table.php`

Creates three tables:
- `staff_modules` - Available modules (Service Requests, Customers, Billing, etc.)
- `staff_module_permissions` - Granular permissions (view, create, edit, delete)
- `user_staff_module_access` - Staff member module access assignments
- `user_staff_module_permissions` - Staff member specific permissions

### 2. Models
- `app/Models/StaffModule.php` - Module definition
- `app/Models/StaffModulePermission.php` - Permission definition
- `app/Models/UserStaffModuleAccess.php` - User module access
- `app/Models/UserStaffModulePermission.php` - User specific permissions

### 3. Controller
**File:** `app/Http/Controllers/Admin/StaffModuleAccessController.php`

Methods:
- `edit($userId)` - Show module access management page
- `update($request, $userId)` - Save module access changes
- `grantAll($userId)` - Grant all modules to staff
- `revokeAll($userId)` - Revoke all modules from staff

### 4. Middleware
**File:** `app/Http/Middleware/CheckStaffModuleAccess.php`

Checks if staff has access to a module before allowing access.

### 5. Views
**File:** `resources/views/admin/staff-module-access/edit.blade.php`

Beautiful UI for managing staff module access with:
- Module cards with toggle switches
- Granular permission checkboxes
- Quick action buttons (Grant All, Revoke All)

### 6. Seeder
**File:** `database/seeders/StaffModuleSeeder.php`

Initializes default modules:
- Service Requests
- Customers
- Technicians
- Subscriptions
- Billing
- Announcements

## Installation Steps

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Seed Default Modules
```bash
php artisan db:seed --class=StaffModuleSeeder
```

### Step 3: Update User Model
The User model has been updated with new methods:
- `hasModuleAccess($moduleKey)` - Check if staff has module access
- `hasStaffPermission($permissionKey)` - Check specific permission
- `getAccessibleModules()` - Get all accessible modules for staff

### Step 4: Register Middleware (Optional)
Add to `app/Http/Kernel.php` if you want to use it:
```php
protected $routeMiddleware = [
    // ... other middleware
    'check.staff.module' => \App\Http\Middleware\CheckStaffModuleAccess::class,
];
```

## Usage

### For Admins

1. **Go to User Management**
   - Navigate to Admin → Users

2. **Find Staff Member**
   - Search or filter for staff members

3. **Click Module Access Button**
   - Green checkmark icon next to staff members
   - Opens module access management page

4. **Customize Access**
   - Toggle modules ON/OFF
   - Set granular permissions (view, create, edit, delete)
   - Click "Save Changes"

5. **Quick Actions**
   - "Grant All Modules" - Give staff access to everything
   - "Revoke All Modules" - Remove all access

### For Staff Members

Staff members will only see modules they have access to in their sidebar. If they try to access a restricted module directly, they'll get a 403 error.

## Database Structure

### staff_modules
```
id, name, key, description, icon, route_prefix, order, is_active, timestamps
```

### staff_module_permissions
```
id, staff_module_id, action, key, description, timestamps
```

### user_staff_module_access
```
id, user_id, staff_module_id, has_access, timestamps
```

### user_staff_module_permissions
```
id, user_id, staff_module_permission_id, granted, timestamps
```

## Helper Methods

### Check Module Access
```php
$user = User::find(1);

// Check if staff has access to a module
if ($user->hasModuleAccess('service_requests')) {
    // User has access
}

// Check specific permission
if ($user->hasStaffPermission('service_requests.create')) {
    // User can create service requests
}

// Get all accessible modules
$modules = $user->getAccessibleModules();
```

## Protecting Routes

### Using Middleware
```php
Route::middleware(['auth', 'role:staff', 'check.staff.module:service_requests'])
    ->group(function () {
        Route::get('/staff/service-requests', ...);
    });
```

### Using Controller Check
```php
public function index()
{
    if (!auth()->user()->hasModuleAccess('service_requests')) {
        abort(403, 'You do not have access to this module.');
    }
    // ... rest of code
}
```

## Sidebar Integration

To hide modules from staff sidebar based on access:

```blade
@foreach(auth()->user()->getAccessibleModules() as $module)
    <li>
        <a href="{{ route($module->route_prefix . '.index') }}">
            {{ $module->name }}
        </a>
    </li>
@endforeach
```

## Adding New Modules

1. Add to `StaffModuleSeeder.php`:
```php
[
    'name' => 'New Module',
    'key' => 'new_module',
    'description' => 'Module description',
    'icon' => 'heroicon-o-icon-name',
    'route_prefix' => 'staff.new_module',
    'order' => 7,
]
```

2. Run seeder:
```bash
php artisan db:seed --class=StaffModuleSeeder
```

## Features

✅ Dynamic module access control
✅ Granular permission management (view, create, edit, delete)
✅ Beautiful admin UI for managing access
✅ Quick action buttons (Grant All, Revoke All)
✅ Automatic sidebar filtering
✅ Route protection with middleware
✅ Easy to extend with new modules
✅ Fully integrated with existing user system

## Routes Added

```
GET  /staff-module-access/{user}/edit          - Show module access form
PUT  /staff-module-access/{user}               - Update module access
POST /staff-module-access/{user}/grant-all     - Grant all modules
POST /staff-module-access/{user}/revoke-all    - Revoke all modules
```

## Notes

- Admins always have access to all modules
- Staff members can only access modules explicitly granted
- Permissions are granular (view, create, edit, delete)
- Module access is checked before allowing page access
- Easy to audit who has access to what

## Troubleshooting

**Staff can't see modules:**
- Check `user_staff_module_access` table
- Ensure `has_access` is set to `true`
- Verify module is active in `staff_modules`

**Middleware not working:**
- Ensure middleware is registered in `Kernel.php`
- Check route has middleware applied
- Verify user role is 'staff'

**Modules not showing in seeder:**
- Run `php artisan db:seed --class=StaffModuleSeeder`
- Check `staff_modules` table for data
- Verify `is_active` is true
