<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $type;

    /**
     * $type can be 'document', 'job', 'event', 'system', etc.
     */
    public function __construct($title, $message, $type = 'system')
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }

    // 🟢 Currently sends to the App Database. (You can easily add 'mail' here later!)
    public function via($notifiable)
    {
        return ['database'];
    }

    // 🟢 Formats the data for the Flutter App
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}