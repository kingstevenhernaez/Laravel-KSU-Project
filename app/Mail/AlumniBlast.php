<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlumniBlast extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    // Receive the message details (Subject, Body)
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject($this->details['subject'])
                    ->view('emails.blast'); // We will create this view next
    }
}