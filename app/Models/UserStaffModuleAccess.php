<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStaffModuleAccess extends Model
{
    use HasFactory;

    protected $table = 'user_staff_module_access';

    protected $fillable = [
        'user_id',
        'staff_module_id',
        'has_access',
    ];

    protected $casts = [
        'has_access' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(StaffModule::class, 'staff_module_id');
    }
}
