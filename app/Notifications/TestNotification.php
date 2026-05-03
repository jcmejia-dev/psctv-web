<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        // store notification in the database
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the notification system is working correctly.',
        ];
    }
}
