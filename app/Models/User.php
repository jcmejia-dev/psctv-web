<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // if you use a role field like 'technician' or 'customer'
        'deactivated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // ✅ This is crucial for remember me functionality
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: one User has one Customer.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    /**
     * Relationship: one User has one Technician.
     */
    public function technician()
    {
        return $this->hasOne(Technician::class, 'user_id');
    }

    /**
     * Optional: quick helper to check role.
     */
    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function role()
{
    return $this->belongsTo(Role::class);
}

public function hasPermission($permissionKey)
{
    if (!$this->role) return false;
    return $this->role->permissions()->where('key', $permissionKey)->exists();
}

/**
 * Relationship: Staff module access
 */
public function staffModuleAccess()
{
    return $this->hasMany(UserStaffModuleAccess::class);
}

/**
 * Relationship: Staff module permissions
 */
public function staffModulePermissions()
{
    return $this->hasMany(UserStaffModulePermission::class);
}

/**
 * Check if staff has access to a module
 */
public function hasModuleAccess($moduleKey)
{
    if ($this->role !== 'staff') {
        return true; // Admins have access to all modules
    }

    $access = $this->staffModuleAccess()
        ->whereHas('module', function ($q) use ($moduleKey) {
            $q->where('key', $moduleKey);
        })
        ->first();

    return $access ? $access->has_access : false;
}

/**
 * Check if staff has specific permission
 */
public function hasStaffPermission($permissionKey)
{
    if ($this->role !== 'staff') {
        return true; // Admins have all permissions
    }

    $permission = $this->staffModulePermissions()
        ->whereHas('permission', function ($q) use ($permissionKey) {
            $q->where('key', $permissionKey);
        })
        ->first();

    return $permission ? $permission->granted : false;
}

/**
 * Get accessible modules for staff
 */
public function getAccessibleModules()
{
    if ($this->role !== 'staff') {
        return StaffModule::where('is_active', true)->orderBy('order')->get();
    }

    return $this->staffModuleAccess()
        ->where('has_access', true)
        ->with('module')
        ->get()
        ->pluck('module')
        ->filter(fn($m) => $m->is_active)
        ->sortBy('order');
}

/**
 * Deactivate the user account
 */
public function deactivate()
{
    $this->update(['deactivated_at' => now()]);
}

/**
 * Reactivate the user account
 */
public function reactivate()
{
    $this->update(['deactivated_at' => null]);
}

/**
 * Check if the user account is deactivated
 */
public function isDeactivated()
{
    return !is_null($this->deactivated_at);
}

}