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

        // 1. Strict Validation (Reduced image size to 2MB maximum to protect server storage)
        $request->validate([
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'mobile'            => 'nullable|string|max:20',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'employment_status' => 'nullable|string',
            'job_title'         => 'nullable|string|max:255',
            'company'           => 'nullable|string|max:255',
        ]);

        // 2. Handle Image Upload & Garbage Collection
        if ($request->hasFile('image')) {
            try {
                // 🟢 AUTO-DELETE: Physically destroy the old image from the Ubuntu server
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                $file = $request->file('image');
                
                // Create a unique filename
                $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
                $storagePath = 'profiles/' . $filename;

                // Process, crop, and compress
                $img = Image::make($file->getRealPath());
                $img->fit(500, 500)->encode('jpg', 80);

                // Save to public storage
                Storage::disk('public')->put($storagePath, (string) $img);
                
                // Update database path
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

        return back()->with('success', 'Profile updated successfully!');
    }

    // 🟢 NEW: Dedicated function to manually delete profile pictures
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
            $user->image = null;
            $user->save();
        }

        return back()->with('success', 'Profile picture permanently removed.');
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

    // 🟢 PHASE 1 & 2: Store Job History and Push Webhook to MIS
    public function storeEmployment(Request $request)
    {
        $request->validate([
            'company_name'    => 'required|string|max:255',
            'job_title'       => 'required|string|max:255',
            'employment_type' => 'required|string|max:100',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = Auth::user();
        $isCurrent = $request->has('is_current');

        $employment = $user->employmentHistories()->create([
            'company_name'    => $request->company_name,
            'job_title'       => $request->job_title,
            'employment_type' => $request->employment_type,
            'start_date'      => $request->start_date,
            'end_date'        => $isCurrent ? null : $request->end_date,
            'is_current'      => $isCurrent,
        ]);

        // 🟢 PHASE 2: AUTOMATED WEBHOOK (REAL-TIME PUSH TO MIS)
        try {
            // The destination URL on the ICT/MIS server
            $webhookUrl = env('MIS_WEBHOOK_URL', 'https://mis.ksu.edu.ph/api/sync/alumni-jobs');
            
            \Illuminate\Support\Facades\Http::timeout(5)->post($webhookUrl, [
                'student_id'   => $user->student_id ?? 'N/A',
                'email'        => $user->email,
                'first_name'   => $user->first_name,
                'last_name'    => $user->last_name,
                'new_job'      => $employment->toArray(),
                'full_history' => $user->employmentHistories()->get()->toArray() // Sends their entire timeline
            ]);
        } catch (\Exception $e) {
            // We use Log::error so if the MIS server is down, the Alumni system doesn't crash
            \Illuminate\Support\Facades\Log::error('MIS Webhook Sync Failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Career experience added and synchronized successfully!');
    }

    public function destroyEmployment($id)
    {
        $employment = \App\Models\EmploymentHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $employment->delete();

        return back()->with('success', 'Experience removed from your timeline.');
    }
}