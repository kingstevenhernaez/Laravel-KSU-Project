<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPost; // Make sure you have this Model too
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(Request $request, $jobId)
    {
        $user = Auth::user();

        // 1. Check if the Job exists
        $job = JobPost::find($jobId);
        if (!$job) {
            return back()->with('error', 'Job not found.');
        }

        // 2. Check if already applied
        $existing = JobApplication::where('user_id', $user->id)
                                  ->where('job_post_id', $jobId)
                                  ->first();

        if ($existing) {
            return back()->with('warning', 'You have already applied for this job!');
        }

        // 3. Create the Application
        JobApplication::create([
            'user_id' => $user->id,
            'job_post_id' => $jobId,
            'status' => 'pending',
            'cover_letter' => $request->input('cover_letter', null),
        ]);

        return back()->with('success', 'Application submitted successfully! The employer will be notified.');
    }
}