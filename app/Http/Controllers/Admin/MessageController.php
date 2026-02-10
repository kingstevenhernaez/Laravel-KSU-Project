<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;                    // <--- IMPORT USER MODEL
use Illuminate\Support\Facades\Mail;    // <--- IMPORT MAIL FACADE
use App\Mail\AlumniBlast;               // <--- IMPORT MAILABLE CLASS

class MessageController extends Controller
{
    // Show the Email Form
    public function create()
    {
        return view('admin.messages.create');
    }

    // Send the Email
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required', // 'all', 'individual'
        ]);

        $details = [
            'subject' => $request->subject,
            'body' => $request->message
        ];

        // 1. Select Recipients
        $recipients = [];

        if ($request->recipient_type == 'all') {
            // Send to ALL Alumni (Role 2)
            // We use pluck to get just the emails, not the whole user object
            $recipients = User::where('role', 2)->pluck('email');
        } 
        elseif ($request->recipient_type == 'individual') {
            // Send to single email
            $recipients = [$request->specific_email];
        }

        // 2. Send Emails
        if(count($recipients) > 0) {
            foreach ($recipients as $email) {
                // We wrap this in a try-catch to prevent one bad email from crashing the whole loop
                try {
                    Mail::to($email)->send(new AlumniBlast($details));
                } catch (\Exception $e) {
                    // If sending fails (e.g., bad internet), just continue to the next person
                    continue; 
                }
            }
            return redirect()->back()->with('success', 'Emails sent successfully (Check logs if using LOG driver).');
        } else {
            return redirect()->back()->with('error', 'No recipients found.');
        }
    }
}