<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('alumni.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validate
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'mobile'     => 'nullable|string|max:20',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Handle Image Upload
        if ($request->hasFile('image')) {
            // Check if old image exists and delete it (Use 'image' column)
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Save new image to 'storage/app/public/profiles'
            $path = $request->file('image')->store('profiles', 'public');
            
            // Assign to 'image' column
            $user->image = $path;
        }

        // 3. Update Text Fields
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->name       = $request->first_name . ' ' . $request->last_name; // Sync full name
        $user->email      = $request->email;
        $user->mobile     = $request->mobile;

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }
}