<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffModule extends Model
{
    use HasFactory;

    protected $table = 'staff_modules';

    protected $fillable = [
        'name',
        'key',
        'description',
        'icon',
        'route_prefix',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function permissions()
    {
        return $this->hasMany(StaffModulePermission::class);
    }

    public function userAccess()
    {
        return $this->hasMany(UserStaffModuleAccess::class);
    }
}
