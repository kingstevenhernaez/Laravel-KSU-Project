<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Fetch only ACTIVE jobs
        $query = Job::where('is_active', true);

        // Optional: Simple Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
        }

        $jobs = $query->latest()->paginate(9); // Show 9 jobs per page

        return view('alumni.jobs.index', compact('jobs'));
    }
}