<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job; // <--- Crucial: Import the Model

class JobController extends Controller
{
    // 1. List all Jobs
    public function index()
    {
        // Get latest jobs, 10 per page
        $jobs = Job::latest()->paginate(10);
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
            'type'        => 'required|string', // Full-time, Remote, etc.
            'description' => 'required',        // The main details
            'deadline'    => 'nullable|date',
            'link'        => 'nullable|url',
        ]);

        Job::create([
            'title'         => $request->title,
            'company'       => $request->company,
            'location'      => $request->location,
            'type'          => $request->type,
            'description'   => $request->description,
            'salary'        => $request->salary,
            'link'          => $request->link,
            'contact_email' => $request->contact_email,
            'deadline'      => $request->deadline,
            'is_active'     => 1
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posted successfully!');
    }

    // 4. Show the "Edit Job" Form
    public function edit($id)
    {
        $job = Job::findOrFail($id);
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

        $job = Job::findOrFail($id);
        
        $job->update([
            'title'         => $request->title,
            'company'       => $request->company,
            'location'      => $request->location,
            'type'          => $request->type,
            'description'   => $request->description,
            'salary'        => $request->salary,
            'link'          => $request->link,
            'contact_email' => $request->contact_email,
            'deadline'      => $request->deadline,
            // We handle 'is_active' via a checkbox usually, checking if it exists in request
            'is_active'     => $request->has('is_active') ? 1 : 0, 
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully!');
    }

    // 6. Delete a Job
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->back()->with('success', 'Job deleted.');
    }
}