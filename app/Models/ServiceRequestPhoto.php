<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ServiceRequestPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'path',
        'label',
        'mime',
    ];

    protected $appends = ['url', 'file_exists', 'full_path'];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function getUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        // Use the correct Laravel storage URL: /storage/service_photos/
        $cleanPath = ltrim($this->path, '/');
        return asset('storage/' . $cleanPath);
    }

    /**
     * Check if the photo file actually exists
     */
    public function getFileExistsAttribute()
    {
        if (!$this->path) {
            return false;
        }
        
        $cleanPath = ltrim($this->path, '/');
        $fullPath = storage_path('app/public/' . $cleanPath);
        return file_exists($fullPath);
    }

    /**
     * Get the full filesystem path
     */
    public function getFullPathAttribute()
    {
        if (!$this->path) {
            return null;
        }
        
        $cleanPath = ltrim($this->path, '/');
        return storage_path('app/public/' . $cleanPath);
    }
}