<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianJob extends Model
{
    protected $fillable = ['service_request_id','technician_id','status','assigned_at','start_time','end_time','notes'];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function photos()
    {
        return $this->hasMany(JobPhoto::class, 'job_id');
    }
}
