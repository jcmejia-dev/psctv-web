<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStaffModulePermission extends Model
{
    use HasFactory;

    protected $table = 'user_staff_module_permissions';

    protected $fillable = [
        'user_id',
        'staff_module_permission_id',
        'granted',
    ];

    protected $casts = [
        'granted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permission()
    {
        return $this->belongsTo(StaffModulePermission::class, 'staff_module_permission_id');
    }
}
