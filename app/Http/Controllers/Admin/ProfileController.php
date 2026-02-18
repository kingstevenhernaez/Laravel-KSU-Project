<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 🟢 Show the Profile Page
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    // 🟢 Handle Password Update
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], // Requires their actual current password
            'password'         => ['required', 'min:8', 'confirmed'], // Requires matching new password
        ]);

        // Update the password in the database
        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Your password has been successfully updated!');
    }
}