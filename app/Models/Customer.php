<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'status',
        'region',
        'province', 
        'municipality',
        'barangay',
        'street',
        'zip_code',
        'address',
        'city',
        'latitude',
        'longitude',
        'gender',
        'dob',
        'phone',
        'plan',
        'meta',
    ];

    protected $casts = [
        'dob' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Invoice::class,
            'subscription_id', // Foreign key on invoices table
            'invoice_id'       // Foreign key on payments table
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Attributes
    |--------------------------------------------------------------------------
    */

    public function getOutstandingBalanceAttribute()
    {
        return $this->subscriptions
            ->flatMap->invoices
            ->whereIn('status', ['unpaid', 'overdue'])
            ->sum('amount_due');
    }

    public function getTotalPaidAttribute()
    {
        return $this->subscriptions
            ->flatMap->invoices
            ->flatMap->payments
            ->sum('amount_paid');
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->barangay,
            $this->municipality,
            $this->province,
            $this->region,
            $this->zip_code
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street ? "Street: {$this->street}" : null,
            $this->barangay ? "Barangay: {$this->barangay}" : null,
            $this->municipality ? "Municipality: {$this->municipality}" : null,
            $this->province ? "Province: {$this->province}" : null,
            $this->region ? "Region: {$this->region}" : null,
            $this->zip_code ? "ZIP: {$this->zip_code}" : null
        ]);

        return implode(', ', $parts);
    }

    /** Calculate age from date of birth */
    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return null;
        }

        return now()->diffInYears($this->dob);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->latest('start_date')
            ->first();
    }

    public function hasOverdueInvoices(): bool
    {
        return $this->subscriptions
            ->flatMap->invoices
            ->contains(fn($invoice) => $invoice->isOverdue());
    }

    public function hasCoordinates(): bool
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }

    /**
     * Get coordinates for the address using the structured data
     */
    public function getCoordinates()
    {
        if (!$this->municipality && !$this->barangay && !$this->street) {
            return [null, null];
        }

        // Build search query from structured data
        $searchQuery = implode(', ', array_filter([
            $this->street,
            $this->barangay,
            $this->municipality,
            'Davao del Norte',
            'Philippines'
        ]));

        return $this->geocodeAddress($searchQuery);
    }

    /**
     * Geocode address using OpenStreetMap
     */
    protected function geocodeAddress($address)
    {
        if (!$address) return [null, null];

        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address) . "&limit=1";

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: YourApp/1.0 (your-email@domain.com)',
                    'Accept: application/json'
                ],
                'timeout' => 10
            ]
        ]);

        try {
            $response = file_get_contents($url, false, $context);
            if ($response === false) return [null, null];

            $data = json_decode($response, true);
            if (isset($data[0]['lat']) && isset($data[0]['lon'])) {
                return [floatval($data[0]['lat']), floatval($data[0]['lon'])];
            }
        } catch (\Exception $e) {
            \Log::error("Geocoding failed: " . $e->getMessage());
        }

        return [null, null];
    }
}