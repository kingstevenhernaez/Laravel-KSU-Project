<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Relaxed validation (Removed strict mimes to ensure Flutter Web blobs pass)
        $request->validate([
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
            'image'  => 'nullable|image|max:5000', 
        ]);

        if ($request->hasFile('image')) {
            // 2. Safely delete the old image
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // 3. Native Laravel Upload (Bypasses Intervention Image completely)
            $file = $request->file('image');
            $path = $file->store('profiles', 'public'); // Automatically generates a unique name
            
            $user->image = $path;
        }

        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json([
            'success' => true, 
            'message' => 'Profile updated successfully!',
            'photo_url' => asset('storage/' . $user->image)
        ]);
    }
}