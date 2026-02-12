<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\GeneralAlumniMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SentEmail; // 🟢 Add this

class EmailController extends Controller
{
    public function index()
    {
        // Fetch only alumni to ensure we use registered profile emails
        $alumni = User::where('role', 'alumni')->get();
        return view('admin.messages.create', compact('alumni'));
    }

public function sentBox(Request $request)
{
    $query = \App\Models\SentEmail::query();

    // Check if there is a search term
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('recipient_email', 'like', '%' . $searchTerm . '%')
              ->orWhere('subject', 'like', '%' . $searchTerm . '%');
        });
    }

    // Get the results with pagination (15 per page)
    // appends(request()->all()) ensures the search term stays in the URL when you click "Next Page"
    $history = $query->latest()->paginate(15)->appends($request->all());

    return view('admin.messages.sent', compact('history'));
}

public function send(Request $request)
{
    // 1. Validate that at least one recipient is selected
    $request->validate([
        'subject'    => 'required|string|max:255',
        'message'    => 'required|string',
        'recipients' => 'required|array|min:1', // 🟢 Ensures it is an array and not empty
    ], [
        'recipients.required' => 'Please select at least one alumni from the list.',
    ]);

    // 2. Fetch users based on the selected emails
    $users = User::whereIn('email', $request->recipients)->get();

    // 3. Loop through and process
    foreach ($users as $user) {
        // Queue the Email
        Mail::to($user->email)->send(new \App\Mail\GeneralAlumniMail($request->subject, $request->message));

        // Log to Sent Box
        \App\Models\SentEmail::create([
            'subject'         => $request->subject,
            'message'         => $request->message,
            'recipient_email' => $user->email,
            'sent_at'         => now(),
        ]);
    }

    return redirect()->route('admin.messages.sent')->with('success', 'Blast initiated! Emails have been added to the queue.');
}

public function destroy($id)
{
    SentEmail::findOrFail($id)->delete();
    return back()->with('success', 'Record deleted from Sent Box.');
}
}