<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class Announcement extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'content',
        'audience',   // all, customers, technicians
        'priority',   // normal, high
        'start_date',
        'end_date',
        // 'status' removed - computed dynamically
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (Auth::check()) {
                $announcement->created_by = Auth::id();
            }

            // Don't set default status - let the accessor compute it based on dates
            // The status will be computed dynamically by getStatusAttribute()
        });
    }

    // Automatically compute current status
    public function getStatusAttribute(): string
    {
        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return 'Scheduled';
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return 'Expired';
        }

        return 'Active';
    }

    // Creator relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for active ones
    public function scopeActive($query)
    {
        return $query
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
