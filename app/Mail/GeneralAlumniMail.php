<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralAlumniMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.general_blast');
    }
}