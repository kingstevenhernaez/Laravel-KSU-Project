<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        return view('alumni.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validate editable fields (Increased max image size to 10MB so phones don't get blocked)
        $request->validate([
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'mobile'            => 'nullable|string|max:20',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'employment_status' => 'nullable|string',
            'job_title'         => 'nullable|string|max:255',
            'company'           => 'nullable|string|max:255',
        ]);

        // 2. Handle Image Upload with Compression & Resizing
        if ($request->hasFile('image')) {
            try {
                // A. Delete old image if it exists
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                $file = $request->file('image');
                
                // B. Create a unique filename (.jpg format)
                $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
                $storagePath = 'profiles/' . $filename;

                // C. Use Intervention Image V2 to process the file
                $img = Image::make($file->getRealPath());
                
                // D. Crop to a perfect 500x500 square and encode to JPG at 80% quality
                $img->fit(500, 500)->encode('jpg', 80);

                // E. Save the compressed image to Laravel's public storage
                Storage::disk('public')->put($storagePath, (string) $img);
                
                // F. Save the relative path to the database
                $user->image = $storagePath;

            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'There was an error processing your image. Please try again.']);
            }
        }

        // 3. Update contact & career info
        $user->email             = $request->email;
        $user->mobile            = $request->mobile;
        $user->employment_status = $request->employment_status;
        $user->job_title         = $request->job_title;
        $user->company           = $request->company;

        $user->save();

        return back()->with('success', 'Profile and Career Info updated successfully!');
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