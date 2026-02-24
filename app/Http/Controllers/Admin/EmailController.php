<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    // 🟢 Display the History List
    public function index(Request $request)
    {
        $query = DB::table('email_logs')->orderBy('sent_at', 'desc');
        
        if ($request->has('search')) {
            $query->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('recipient_email', 'like', "%{$request->search}%");
        }

        $history = $query->paginate(10);
        return view('admin.emails.index', compact('history'));
    }

    // 🟢 THE FIX: Catch any stray links asking for 'sentBox' and send them to 'index'
    public function sentBox(Request $request)
    {
        return $this->index($request);
    }

    // 🟢 Display the Compose/New Blast Form
    public function create()
    {
        // Fetch all verified alumni to populate the dropdown
        $alumni = User::where('role', 2)
                      ->where('status', 1)
                      ->orderBy('first_name', 'asc')
                      ->get();

        return view('admin.emails.create', compact('alumni'));
    }

    // 🟢 Handle the Actual Sending & Recording
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required',
            'recipient_type' => 'required' 
        ]);

        $subject = $request->subject;
        $content = $request->message;

        // Determine who gets the email
        if ($request->recipient_type === 'all') {
            $recipients = User::where('role', 2)->pluck('email');
            $logRecipient = "All Alumni (" . $recipients->count() . ")";
        } else {
            $recipients = [$request->specific_email];
            $logRecipient = $request->specific_email;
        }

        // Send logic (Ensure your .env SMTP is configured)
        foreach ($recipients as $email) {
            Mail::raw($content, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
        }

        // Record in the Sent Box (History)
        DB::table('email_logs')->insert([
            'subject' => $subject,
            'message' => $content,
            'recipient_email' => $logRecipient,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.emails.index')->with('success', 'Email blast sent and recorded!');
    }
}