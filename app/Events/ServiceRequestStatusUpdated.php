<?php
// app/Events/ServiceRequestStatusUpdated.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServiceRequest;

class ServiceRequestStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest->load([
            'technician.user', 
            'photos', 
            'customer.user'
        ]);
    }

    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel('service-request.' . $this->serviceRequest->id),
        ];

        // Add customer channel if exists
        if ($this->serviceRequest->customer_id) {
            $channels[] = new PrivateChannel('customer.' . $this->serviceRequest->customer_id);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'ServiceRequestStatusUpdated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->serviceRequest->id,
            'status' => $this->serviceRequest->status,
            'service_type' => $this->serviceRequest->service_type,
            'address' => $this->serviceRequest->address,
            'notes' => $this->serviceRequest->notes,
            'technician' => $this->serviceRequest->technician ? [
                'id' => $this->serviceRequest->technician->id,
                'full_name' => $this->serviceRequest->technician->full_name,
                'specialization' => $this->serviceRequest->technician->specialization,
                'phone' => $this->serviceRequest->technician->phone,
                'email' => $this->serviceRequest->technician->user->email ?? null,
            ] : null,
            'assigned_at' => $this->serviceRequest->assigned_at?->toISOString(),
            'started_at' => $this->serviceRequest->started_at?->toISOString(),
            'completed_at' => $this->serviceRequest->completed_at?->toISOString(),
            'report' => $this->serviceRequest->report,
            'customer_name' => $this->serviceRequest->display_customer_name,
            'updated_at' => $this->serviceRequest->updated_at->toISOString(),
            'progress_percent' => $this->serviceRequest->progress_percent,
            'latitude' => $this->serviceRequest->latitude,
            'longitude' => $this->serviceRequest->longitude,
        ];
    }

    public function broadcastWhen(): bool
    {
        return !is_null($this->serviceRequest->customer_id);
    }
}