<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;
use App\Models\Announcement;

class NewAnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The announcement instance.
     *
     * @var \App\Models\Announcement
     */
    protected $announcement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification delivery channels.
     */
    public function via($notifiable)
    {
        // If announcement is high priority → also send via email
        if ($this->announcement->priority === 'high') {
            return ['database', 'broadcast', 'mail'];
        }

        return ['database', 'broadcast'];
    }

    /**
     * Data saved in database.
     */
    public function toDatabase($notifiable)
    {
        // Determine the appropriate route based on user role
        $url = $this->getAnnouncementUrl($notifiable);

        return [
            'announcement_id' => $this->announcement->id,
            'title'           => $this->announcement->title,
            'message'         => strip_tags($this->announcement->content),
            'audience'        => $this->announcement->audience, // e.g., 'all', 'staff', 'technician'
            'priority'        => ucfirst($this->announcement->priority),
            'url'             => $url,
            'icon'            => $this->getPriorityIcon(),
            'created_at'      => now()->toDateTimeString(),
        ];
    }

    /**
     * Data sent through broadcast (for real-time updates via Pusher/Echo).
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'announcement_id' => $this->announcement->id,
            'title'           => $this->announcement->title,
            'message'         => strip_tags($this->announcement->content),
            'audience'        => $this->announcement->audience,
            'priority'        => ucfirst($this->announcement->priority),
            'url'             => $this->getAnnouncementUrl($notifiable),
            'icon'            => $this->getPriorityIcon(),
            'created_at'      => now()->toDateTimeString(),
        ]);
    }

    /**
     * Email representation (for high-priority announcements).
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📢 New Announcement: ' . $this->announcement->title)
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . ',')
            ->line(strip_tags($this->announcement->content))
            ->action('View Announcement', route('announcements.show', $this->announcement->id))
            ->line('This message was sent because you are part of the announcement audience.')
            ->salutation('— The System Team');
    }

    /**
     * Define the broadcast channel.
     */
    public function broadcastOn()
    {
        // If you plan to send to all users
        if ($this->announcement->audience === 'all') {
            return new Channel('announcements');
        }

        // Otherwise, send per role-based channel
        return new Channel('announcements.' . strtolower($this->announcement->audience));
    }

    /**
     * Customize the broadcast event name.
     */
    public function broadcastAs()
    {
        return 'new-announcement';
    }

    /**
     * Helper: Priority icons (for frontend display)
     */
    private function getPriorityIcon(): string
    {
        return match ($this->announcement->priority) {
            'high'   => '🔥',
            'medium' => '📣',
            'low'    => '📰',
            default  => '🔔',
        };
    }

    /**
     * Get the appropriate announcement URL based on user role
     */
    private function getAnnouncementUrl($notifiable): string
    {
        // Check user role and return appropriate absolute route
        if ($notifiable->role === 'staff') {
            return url()->route('staff.announcements.show', $this->announcement->id);
        }

        // Default to general announcements route for admin/technician
        return url()->route('announcements.show', $this->announcement->id);
    }
}
