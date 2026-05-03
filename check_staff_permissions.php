<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\StaffModule;
use App\Models\UserStaffModuleAccess;
use App\Models\UserStaffModulePermission;

echo "Checking staff permissions...\n";

// Get all staff users
$staffUsers = User::where('role', 'staff')->get();

if ($staffUsers->isEmpty()) {
    echo "No staff users found.\n";
    exit;
}

foreach ($staffUsers as $user) {
    echo "User: {$user->name} (ID: {$user->id})\n";

    // Check module access
    $billingModule = StaffModule::where('key', 'billing')->first();
    if ($billingModule) {
        $access = UserStaffModuleAccess::where('user_id', $user->id)
            ->where('staff_module_id', $billingModule->id)
            ->first();

        echo "  Billing module access: " . ($access && $access->has_access ? 'YES' : 'NO') . "\n";
    }

    // Check permissions
    $permissions = UserStaffModulePermission::where('user_id', $user->id)
        ->with('permission')
        ->whereHas('permission', function($q) {
            $q->where('key', 'like', 'billing.%');
        })
        ->get();

    echo "  Billing permissions:\n";
    foreach ($permissions as $perm) {
        echo "    {$perm->permission->key}: " . ($perm->granted ? 'GRANTED' : 'DENIED') . "\n";
    }

    echo "\n";
}
