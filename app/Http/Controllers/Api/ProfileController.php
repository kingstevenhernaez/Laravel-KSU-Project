<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validation logic mirrored from your web base
        $request->validate([
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        // 2. Handle Image Upload & Garbage Collection
        if ($request->hasFile('image')) {
            // Delete old image if it exists to maximize storage
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $file = $request->file('image');
            $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
            $storagePath = 'profiles/' . $filename;

            // Shrink and compress image to maximize server storage
            $img = Image::make($file->getRealPath());
            $img->fit(500, 500)->encode('jpg', 80);

            Storage::disk('public')->put($storagePath, (string) $img);
            $user->image = $storagePath;
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