<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServiceRequest;

class ServiceRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceRequest;
    public $customerName;
    public $serviceType;
    public $address;
    public $createdAt;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
        $this->customerName = $serviceRequest->customer_name;
        $this->serviceType = $serviceRequest->service_type;
        $this->address = $serviceRequest->address;
        $this->createdAt = $serviceRequest->created_at ? $serviceRequest->created_at->toDateTimeString() : now()->toDateTimeString();
    }

    public function broadcastOn()
    {
        // FIX: Broadcast to channels that actually exist and are accessible
        return [
            new PrivateChannel('admin.notifications'),
            new Channel('service-requests'),
        ];
    }

    public function broadcastAs()
    {
        return 'ServiceRequestCreated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->serviceRequest->id,
            'title' => 'New Service Request',
            'message' => "New {$this->serviceType} request from {$this->customerName}",
            'customer_name' => $this->customerName,
            'service_type' => $this->serviceType,
            'address' => $this->address,
            'timestamp' => $this->createdAt,
            'type' => 'service_request',
            'source' => 'mobile_app',
            'url' => '/admin/service_requests/' . $this->serviceRequest->id
        ];
    }
}