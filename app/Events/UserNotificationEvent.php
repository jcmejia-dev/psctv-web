<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $title;
    public $message;
    public $url;
    public $userId;

    public function __construct($userId, $title, $message, $url = '#')
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'url'     => $this->url,
        ];
    }
}
