<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\ServiceRequest;
use App\Models\User;

class TechnicianAssigned extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $serviceRequest;
    public $assigner;

    /**
     * Create a new notification instance.
     */
    public function __construct(ServiceRequest $serviceRequest, User $assigner = null)
    {
        $this->serviceRequest = $serviceRequest;
        $this->assigner = $assigner;
    }

    /**
     * Notification channels
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Email notification
     */
    public function toMail($notifiable)
    {
        $techName = property_exists($notifiable, 'full_name') ? $notifiable->full_name : $notifiable->name;
        $assignerName = $this->assigner ? $this->assigner->name : 'System Administrator';
        $customerName = $this->serviceRequest->customer?->user?->name ?? $this->serviceRequest->customer_name ?? 'Customer';

        $greeting = ($notifiable->id === $this->serviceRequest->technician?->user?->id)
            ? "Hello {$techName}," // Technician
            : "Hello {$customerName},"; // Customer

        return (new MailMessage)
            ->subject('📋 New Assignment: Request #REQ-' . $this->serviceRequest->id)
            ->greeting($greeting)
            ->line('A technician has been assigned to the service request.')
            ->line('📍 Customer: ' . $customerName)
            ->line('🛠 Service Type: ' . $this->serviceRequest->service_type)
            ->line('👤 Assigned By: ' . $assignerName)
            ->action('View Request Details', route('service_requests.show', $this->serviceRequest->id))
            ->line('Please check and proceed accordingly.');
    }

    /**
     * Database notification
     */
    public function toDatabase($notifiable)
    {
        $techName = property_exists($notifiable, 'full_name') ? $notifiable->full_name : $notifiable->name;
        $assignerName = $this->assigner ? $this->assigner->name : 'System';
        $customerName = $this->serviceRequest->customer?->user?->name ?? $this->serviceRequest->customer_name ?? 'Customer';

        return [
            'request_id'   => $this->serviceRequest->id,
            'request_code' => 'REQ-' . $this->serviceRequest->id,
            'customer'     => $customerName,
            'service'      => $this->serviceRequest->service_type,
            'status'       => $this->serviceRequest->status,
            'technician'   => $techName,
            'assigner'     => $assignerName,
            'url'          => route('service_requests.show', $this->serviceRequest->id),
            'timestamp'    => now()->toDateTimeString(),
        ];
    }

    /**
     * Broadcast version for real-time updates
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toDatabase($notifiable),
        ]);
    }

    /**
     * Private broadcast channel for this user
     */
    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\PrivateChannel('App.Models.User.' . $this->notifiable->id);
    }

    /**
     * Event name for broadcast
     */
    public function broadcastAs()
    {
        return 'TechnicianAssignedNotification';
    }
}
