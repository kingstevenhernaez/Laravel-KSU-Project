<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\ApplicationStatusUpdated; 

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPost::latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'type'        => 'required|string', 
            'description' => 'required',        
            'deadline'    => 'nullable|date',
        ]);

        JobPost::create([
            'title'       => $request->title,
            'company'     => $request->company, 
            'location'    => $request->location,
            'type'        => $request->type,     
            'description' => $request->description,
            'salary'      => $request->salary,
            'deadline'    => $request->deadline,
            'is_active'   => 1, 
        ]);

        // [NEW] Broadcast Notification to all Alumni
        $alumni = \App\Models\User::where('role', 2)->where('status', 1)->get(); 
        $title = "New Career Opportunity!";
        $message = "A new job has been posted: " . $request->title;
        
        \Illuminate\Support\Facades\Notification::send($alumni, new \App\Notifications\AppNotification($title, $message, 'job'));
        
        return redirect()->route('admin.jobs.index')->with('success', 'Job posted successfully!');
    }

    public function edit($id)
    {
        $job = JobPost::findOrFail($id);
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'required',
        ]);

        $job = JobPost::findOrFail($id);
        
        $job->update([
            'title'       => $request->title,
            'company'     => $request->company,
            'location'    => $request->location,
            'type'        => $request->type,
            'description' => $request->description,
            'salary'      => $request->salary,
            'deadline'    => $request->deadline,
            'is_active'   => $request->has('is_active') ? 1 : 0, 
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $job = JobPost::findOrFail($id);
        $job->delete();

        return redirect()->back()->with('success', 'Job deleted.');
    }

   public function applicants($id)
    {
        $job = \App\Models\JobPost::findOrFail($id);
        
        $applicants = \App\Models\JobApplication::with('user')
                        ->where('job_post_id', $id)
                        ->latest()
                        ->get();

        return view('admin.jobs.applicants', compact('job', 'applicants'));
    }

   public function updateApplicationStatus(\Illuminate\Http\Request $request, $id)
    {
        $application = \App\Models\JobApplication::with(['user', 'job'])->findOrFail($id);
        $application->status = $request->status;
        $application->save();

        Mail::to($application->user->email)->send(new ApplicationStatusUpdated($application));

        return back()->with('success', 'Applicant status updated and email notification sent!');
    }
}