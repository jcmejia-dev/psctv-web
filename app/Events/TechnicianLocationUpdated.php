<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServiceRequest;

class TechnicianLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceRequestId;
    public $latitude;
    public $longitude;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequestId = $serviceRequest->id;
        $this->latitude = $serviceRequest->technician->latitude;
        $this->longitude = $serviceRequest->technician->longitude;
    }

    public function broadcastOn()
    {
        return ['service-request.' . $this->serviceRequestId];
    }

    public function broadcastAs()
    {
        return 'TechnicianLocationUpdated';
    }
}
