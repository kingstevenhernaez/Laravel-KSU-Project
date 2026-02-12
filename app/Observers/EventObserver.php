<?php
namespace App\Observers;

use App\Models\Event;
use App\Models\User;
use App\Mail\EventNotificationMail;
use Illuminate\Support\Facades\Mail;

class EventObserver
{
    public function created(Event $event)
    {
        // Get all alumni email addresses
        $alumniEmails = User::where('role', 'alumni')->pluck('email');

        foreach ($alumniEmails as $email) {
            // This will use the queue we set up earlier!
            Mail::to($email)->queue(new EventNotificationMail($event));
        }
    }
}