<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffModulePermission extends Model
{
    use HasFactory;

    protected $table = 'staff_module_permissions';

    protected $fillable = [
        'staff_module_id',
        'action',
        'key',
        'description',
    ];

    public function module()
    {
        return $this->belongsTo(StaffModule::class, 'staff_module_id');
    }

    public function userPermissions()
    {
        return $this->hasMany(UserStaffModulePermission::class);
    }
}
