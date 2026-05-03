<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ServiceCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * Channels used: mail, database, and broadcast
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Email notification - Customize for different recipients
     */
    public function toMail($notifiable)
    {
        $technician = $this->serviceRequest->technician;
        $technicianName = $technician?->full_name
            ?? $technician?->user?->name
            ?? 'the assigned technician';

        $customerName = $this->serviceRequest->customer?->user?->name
            ?? $this->serviceRequest->customer_name
            ?? 'Unknown Customer';

        // Customize email based on recipient role
        $isAdmin = $notifiable->role === 'admin' || $notifiable->role === 'staff';
        
        if ($isAdmin) {
            // Email for admin/staff
            return (new MailMessage)
                ->subject('✅ Service Request Completed - #REQ-' . $this->serviceRequest->id)
                ->line('A service request has been marked as completed.')
                ->line('📋 Request ID: #REQ-' . $this->serviceRequest->id)
                ->line('👤 Customer: ' . $customerName)
                ->line('🔧 Technician: ' . $technicianName)
                ->line('🛠 Service Type: ' . $this->serviceRequest->service_type)
                ->line('📅 Completed At: ' . $this->serviceRequest->completed_at?->format('M j, Y g:i A') ?? 'Just now')
                ->action('Review Completed Request', url(route('service_requests.show', $this->serviceRequest->id)))
                ->line('Please review the completed service request.');
        } else {
            // Email for customer/technician
            return (new MailMessage)
                ->subject('Service Request Completed - #REQ-' . $this->serviceRequest->id)
                ->line("Service Request #REQ-{$this->serviceRequest->id} has been marked as completed.")
                ->line('Customer: ' . $customerName)
                ->line('Technician: ' . $technicianName)
                ->line('Service Type: ' . $this->serviceRequest->service_type)
                ->action('View Request', url(route('service_requests.show', $this->serviceRequest->id)))
                ->line('Thank you for using our service.');
        }
    }

    /**
     * Stored in database notifications
     */
    public function toArray($notifiable)
    {
        $technician = $this->serviceRequest->technician;
        $technicianName = $technician?->full_name
            ?? $technician?->user?->name
            ?? 'Unknown Technician';

        $customerName = $this->serviceRequest->customer?->user?->name
            ?? $this->serviceRequest->customer_name
            ?? 'Unknown Customer';

        $isAdmin = $notifiable->role === 'admin' || $notifiable->role === 'staff';

        return [
            'service_request_id' => $this->serviceRequest->id,
            'customer_name'      => $customerName,
            'technician_name'    => $technicianName,
            'service_type'       => $this->serviceRequest->service_type,
            'status'             => $this->serviceRequest->status,
            'completed_at'       => $this->serviceRequest->completed_at?->format('Y-m-d H:i'),
            'title'              => $isAdmin ? 'Service Request Completed - Review Needed' : 'Service Request Completed',
            'message'            => $isAdmin 
                ? "Service Request #REQ-{$this->serviceRequest->id} for {$customerName} has been completed by {$technicianName}. Please review."
                : "Service Request #REQ-{$this->serviceRequest->id} for {$this->serviceRequest->service_type} has been completed by {$technicianName}.",
            'url'                => route('service_requests.show', $this->serviceRequest->id),
            'type'               => 'service_completed',
            'priority'           => $isAdmin ? 'high' : 'normal',
        ];
    }

    /**
     * Broadcast real-time event
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable),
            'created_at' => now(),
            'id' => $this->id,
        ]);
    }

    /**
     * Broadcast on appropriate channels
     */
    public function broadcastOn()
    {
        // Use user's private channel
        return new PrivateChannel('App.Models.User.' . $this->notifiable->id);
    }

    /**
     * Name of broadcast event
     */
    public function broadcastAs()
    {
        return 'ServiceCompletedNotification';
    }
}