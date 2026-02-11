<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\GeneralAlumniMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        $alumni = User::where('role', 'alumni')->get();
        return view('admin.emails.index', compact('alumni'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required',
            'recipients' => 'required|array',
        ]);

        $subject = $request->subject;
        $content = $request->message;

        foreach ($request->recipients as $email) {
            Mail::to($email)->send(new GeneralAlumniMail($subject, $content));
        }

        return back()->with('success', 'Emails are being sent to ' . count($request->recipients) . ' recipients!');
    }
}