<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost; // Ensure this matches your Model name

class JobController extends Controller
{
    // 1. List all Jobs
    public function index()
    {
        $jobs = JobPost::latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    // 2. Show the "Create New Job" Form
    public function create()
    {
        return view('admin.jobs.create');
    }

    // 3. Save the New Job to Database
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
            
            // 🟢 CORRECT MAPPING
            'company'     => $request->company, 
            'location'    => $request->location,
            'type'        => $request->type,     
            'description' => $request->description,
            'salary'      => $request->salary,
            'deadline'    => $request->deadline,
            
            'is_active'   => 1, // Force Active
            
            // 🔴 REMOVED 'posted_by' (This was causing your crash)
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posted successfully!');
    }

    // 4. Show the "Edit Job" Form
    public function edit($id)
    {
        $job = JobPost::findOrFail($id);
        return view('admin.jobs.edit', compact('job'));
    }

    // 5. Update an Existing Job
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

    // 6. Delete a Job
    public function destroy($id)
    {
        $job = JobPost::findOrFail($id);
        $job->delete();

        return redirect()->back()->with('success', 'Job deleted.');
    }

    // 7. View Applicants
   public function applicants($id)
    {
        $job = \App\Models\JobPost::findOrFail($id);
        
        // Fetch applications with User data
        $applicants = \App\Models\JobApplication::with('user')
                        ->where('job_post_id', $id)
                        ->latest()
                        ->get();

        return view('admin.jobs.applicants', compact('job', 'applicants'));
    }

    // 🟢 2. Update Status (The Feedback System)
    public function updateApplicationStatus(\Illuminate\Http\Request $request, $id)
    {
        $application = \App\Models\JobApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return back()->with('success', 'Applicant status updated to ' . ucfirst($request->status));
    }
}