<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function build()
{
    return $this->subject('New Alumni Event: ' . $this->event->title)
        ->html("
            <h2>{$this->event->title}</h2>
            <p><strong>When:</strong> {$this->event->date->format('M d, Y h:i A')}</p>
            <p><strong>Where:</strong> {$this->event->location}</p>
            <p>{$this->event->description}</p>
        ");
}
}