<?php

namespace App\Events;

use App\Models\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAnnouncementCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function broadcastOn()
    {
        // public channel — anyone can receive announcements
        return new Channel('announcements');
    }

    public function broadcastAs()
    {
        // event name used on Echo listener
        return 'NewAnnouncementCreated';
    }

    public function broadcastWith()
    {
        return [
            'id'        => $this->announcement->id,
            'title'     => $this->announcement->title,
            'message'   => $this->announcement->content,
            'priority'  => $this->announcement->priority,
            'audience'  => $this->announcement->audience,
            'url'       => route('announcements.show', $this->announcement->id),
            'created_at' => $this->announcement->created_at->diffForHumans(),
        ];
    }
}
