<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User; // 🟢 Import the User Model

class ProfileController extends Controller
{
    public function index()
    {
        return view('alumni.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Check if Image was uploaded
        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/profile');
            
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Save the file
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($uploadPath, $imageName);
            
            // Assign to User
            $user->image = 'uploads/profile/' . $imageName;
            
            // 🛑 DEBUG: Uncomment this line to see if the path is being set
            // dd("File Uploaded! Path is: " . $user->image);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        
        // 2. Save to Database
        $saved = $user->save();

        // 🛑 DEBUG: If it didn't save, this will tell us
        if (!$saved) {
            dd("Error: Database refused to save.");
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check password on the FRESH instance
        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }
}