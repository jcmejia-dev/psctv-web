<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\TechnicianAssigned;
use App\Notifications\ServiceCompleted;
use App\Events\ServiceRequestStatusUpdated;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'customer_name',
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'service_type',
        'notes',
        'status',
        'technician_id',
        'assigned_at',
        'started_at',
        'completed_at',
        'report',
        'rating',
        'rated_at',
        'created_by',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
        'rated_at'     => 'datetime',
    ];

    protected $with = ['technician'];
    protected $appends = ['progress_percent', 'display_customer_name'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => \App\Events\ServiceRequestStatusUpdated::class,
    ];

    /* ------------------------------------------------------------
     | Relationships
     |------------------------------------------------------------ */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function photos()
    {
        return $this->hasMany(ServiceRequestPhoto::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ------------------------------------------------------------
     | Rating Scopes
     |------------------------------------------------------------ */
    
    /** Scope for rated requests */
    public function scopeRated($query)
    {
        return $query->whereNotNull('rating');
    }

    /** Scope for rateable requests (completed but not rated) */
    public function scopeRateable($query)
    {
        return $query->where('status', 'completed')->whereNull('rating');
    }

    /* ------------------------------------------------------------
     | Enhanced Address Geocoding with Better Accuracy
     |------------------------------------------------------------ */
    public function geocodeAddress(): bool
    {
        if (empty($this->address)) {
            Log::warning("Cannot geocode empty address for ServiceRequest #{$this->id}");
            return false;
        }

        try {
            Log::info("🌍 Geocoding address: '{$this->address}'");

            // Clean and format the address for better geocoding
            $cleanedAddress = $this->cleanAddressForGeocoding($this->address);
            
            Log::info("🧹 Cleaned address for geocoding: '{$cleanedAddress}'");

            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'PCTVS-Service-System/1.0 (admin@pctvs.local)',
                    'Accept-Language' => 'en',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $cleanedAddress,
                    'format' => 'json',
                    'limit' => 1,
                    'countrycodes' => 'ph',
                    'addressdetails' => 1,
                    'zoom' => 18,
                    'polygon_geojson' => 1,
                ]);

            if ($response->successful() && !empty($response->json())) {
                $data = $response->json()[0];

                $this->latitude = (float) $data['lat'];
                $this->longitude = (float) $data['lon'];
                $this->save();

                Log::info("✅ Successfully geocoded '{$this->address}' → [{$this->latitude}, {$this->longitude}] for ServiceRequest #{$this->id}");
                
                // Log detailed location information
                Log::info("📍 Location details", [
                    'display_name' => $data['display_name'] ?? 'N/A',
                    'type' => $data['type'] ?? 'N/A',
                    'class' => $data['class'] ?? 'N/A',
                    'importance' => $data['importance'] ?? 'N/A',
                    'cleaned_address' => $cleanedAddress
                ]);
                
                return true;
            } else {
                Log::warning("⚠️ Geocoding failed for '{$this->address}'. Response status: " . $response->status());
                
                // Try alternative geocoding with different address format
                if (!$this->tryAlternativeGeocoding($this->address)) {
                    Log::error("❌ All geocoding attempts failed for '{$this->address}'");
                    return false;
                }
                
                return true;
            }
        } catch (\Throwable $e) {
            Log::error("💥 Geocoding exception for '{$this->address}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clean and format address for better geocoding results
     */
    private function cleanAddressForGeocoding(string $address): string
    {
        // Remove extra spaces and commas
        $cleaned = preg_replace('/\s+/', ' ', trim($address));
        $cleaned = preg_replace('/,\s*,/', ',', $cleaned);
        
        // Ensure Philippines is included for better geocoding
        if (!str_contains(strtolower($cleaned), 'philippines') && !str_contains(strtolower($cleaned), 'ph')) {
            $cleaned .= ', Philippines';
        }
        
        // Common address corrections for Davao region
        $replacements = [
            '/\bpanabo\b/i' => 'Panabo City',
            '/\bdavao\s+del\s+norte\b/i' => 'Davao del Norte',
            '/\bddn\b/i' => 'Davao del Norte',
            '/\bbrgy\.?\b/i' => 'Barangay',
            '/\bpob\.?\b/i' => 'Poblacion',
        ];
        
        $cleaned = preg_replace(array_keys($replacements), array_values($replacements), $cleaned);
        
        return $cleaned;
    }

    /**
     * Try alternative geocoding methods if primary fails
     */
    private function tryAlternativeGeocoding(string $originalAddress): bool
    {
        $alternativeFormats = [
            $originalAddress,
            $this->extractCityProvince($originalAddress),
            $this->extractCityOnly($originalAddress),
            $this->extractKeyLocation($originalAddress),
        ];

        foreach ($alternativeFormats as $format) {
            if (empty($format)) continue;

            try {
                Log::info("🔄 Trying alternative geocoding format: '{$format}'");
                
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'PCTVS-Service-System/1.0 (admin@pctvs.local)',
                    ])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $format,
                        'format' => 'json',
                        'limit' => 1,
                        'countrycodes' => 'ph',
                    ]);

                if ($response->successful() && !empty($response->json())) {
                    $data = $response->json()[0];
                    $this->latitude = (float) $data['lat'];
                    $this->longitude = (float) $data['lon'];
                    $this->save();

                    Log::info("✅ Alternative geocoding successful with format: '{$format}' → [{$this->latitude}, {$this->longitude}]");
                    return true;
                }
            } catch (\Throwable $e) {
                Log::warning("Alternative geocoding failed for format '{$format}': " . $e->getMessage());
            }
        }

        return false;
    }

    private function extractCityProvince(string $address): string
    {
        // Extract city and province from address
        if (preg_match('/([^,]+(?:City|Municipality)),\s*([^,]+(?:Province|Region))/i', $address, $matches)) {
            return $matches[1] . ', ' . $matches[2] . ', Philippines';
        }
        return '';
    }

    private function extractCityOnly(string $address): string
    {
        // Extract just the city
        if (preg_match('/([^,]+(?:City|Municipality))/i', $address, $matches)) {
            return $matches[1] . ', Philippines';
        }
        return '';
    }

    private function extractKeyLocation(string $address): string
    {
        // Extract the most recognizable location name
        $parts = explode(',', $address);
        foreach ($parts as $part) {
            $trimmed = trim($part);
            if (preg_match('/(?:City|Municipality|Province|Region|Barangay)/i', $trimmed)) {
                return $trimmed . ', Philippines';
            }
        }
        return trim($parts[0] ?? $address) . ', Philippines';
    }

    /* ------------------------------------------------------------
     | Broadcasting and Real-time Updates
     |------------------------------------------------------------ */
    
    /**
     * Assign technician and broadcast update
     */
    public function assignTo(int $technicianId, ?int $assignerId = null): void
    {
        if ($this->technician_id === $technicianId && $this->status === 'assigned') {
            return;
        }

        $this->update([
            'technician_id' => $technicianId,
            'status'        => 'assigned',
            'assigned_at'   => now(),
        ]);

        // 🔥 Broadcast the assignment update
        broadcast(new ServiceRequestStatusUpdated($this));

        try {
            $technician = $this->technician;
            if ($technician) {
                $technician->notify(new TechnicianAssigned($this, Auth::user()));
            }
        } catch (\Throwable $e) {
            Log::error("❌ Failed to notify Technician #{$technicianId}: " . $e->getMessage());
        }
    }

    /**
     * Update status and broadcast to mobile app
     */
    public function updateStatus(string $status, ?string $report = null): bool
    {
        $oldStatus = $this->status;
        
        $updateData = ['status' => $status];
        
        // Set timestamps based on status
        if ($status === 'assigned' && !$this->assigned_at) {
            $updateData['assigned_at'] = now();
        } elseif ($status === 'in-progress' && !$this->started_at) {
            $updateData['started_at'] = now();
        } elseif ($status === 'completed' && !$this->completed_at) {
            $updateData['completed_at'] = now();
            $updateData['report'] = $report ?? $this->report;
        } elseif ($status === 'cancelled') {
            $updateData['completed_at'] = null;
            $updateData['started_at'] = null;
        }

        $this->update($updateData);

        // 🔥 Broadcast the status update
        broadcast(new ServiceRequestStatusUpdated($this));

        Log::info("Service request #{$this->id} status changed from '{$oldStatus}' to '{$status}'");

        return true;
    }

    public function notifyCompletion(): int
    {
        Log::info("🔔 notifyCompletion() CALLED for service request #{$this->id}");
        
        $totalNotifications = 0;
        
        try {
            // Load relationships
            $this->load(['technician.user', 'customer.user']);
            
            // Create notification instance
            $notification = new ServiceCompleted($this);
            
            // 🔔 Notify customer
            if ($this->customer?->user) {
                try {
                    $this->customer->user->notify($notification);
                    $totalNotifications++;
                    Log::info("✅ Notified customer #{$this->customer->user->id} about completion of request #{$this->id}");
                } catch (\Throwable $e) {
                    Log::error("❌ Failed to notify customer (User ID: {$this->customer->user->id}): {$e->getMessage()}");
                }
            }
            
            // 🔔 Notify technician
            if ($this->technician?->user) {
                try {
                    $this->technician->user->notify($notification);
                    $totalNotifications++;
                    Log::info("✅ Notified technician #{$this->technician->user->id} about completion of request #{$this->id}");
                } catch (\Throwable $e) {
                    Log::error("❌ Failed to notify technician (User ID: {$this->technician->user->id}): {$e->getMessage()}");
                }
            }
            
            // 🔔 Notify admins and staff
            $admins = User::whereIn('role', ['admin', 'staff'])->get();
            if ($admins->count() > 0) {
                try {
                    \Illuminate\Support\Facades\Notification::send($admins, $notification);
                    $totalNotifications += $admins->count();
                    Log::info("✅ Notified {$admins->count()} admin/staff users about completion of request #{$this->id}");
                } catch (\Throwable $e) {
                    Log::error("❌ Failed to notify admins about completion for request #{$this->id}: {$e->getMessage()}");
                }
            }
            
            Log::info("📊 Total notifications sent for completion: {$totalNotifications}");
            
        } catch (\Throwable $e) {
            Log::error("💥 Error in notifyCompletion for request #{$this->id}: {$e->getMessage()}");
        }
        
        return $totalNotifications;
    }

    /* ------------------------------------------------------------
     | Scopes
     |------------------------------------------------------------ */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'in-progress']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssignedTo($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    /* ------------------------------------------------------------
     | UI Helpers
     |------------------------------------------------------------ */
    public function getProgressPercentAttribute(): int
    {
        return match ($this->status) {
            'pending'     => 0,
            'assigned'    => 33,
            'in-progress' => 66,
            'completed'   => 100,
            default       => 0,
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['color' => 'gray', 'text' => 'Pending'],
            'assigned' => ['color' => 'blue', 'text' => 'Assigned'],
            'in-progress' => ['color' => 'yellow', 'text' => 'In Progress'],
            'completed' => ['color' => 'green', 'text' => 'Completed'],
            'cancelled' => ['color' => 'red', 'text' => 'Cancelled'],
            default => ['color' => 'gray', 'text' => $this->status],
        };
    }

    public function getDisplayCustomerNameAttribute(): string
    {
        if ($this->customer && $this->customer->user) {
            return $this->customer->user->name;
        }

        if (!empty($this->customer_name)) {
            return "{$this->customer_name} (Walk-in)";
        }

        if (!$this->customer_id) {
            return 'Walk-in Customer';
        }

        return '— No Customer Linked —';
    }

    public function getFormattedIdAttribute(): string
    {
        return 'REQ-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function distanceTo($latitude, $longitude): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        $earthRadius = 6371;

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}