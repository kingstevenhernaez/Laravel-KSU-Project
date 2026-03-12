<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        // Fetch the latest active jobs
        $jobs = JobPost::where('is_active', 1)
                       ->orderBy('created_at', 'desc')
                       ->take(15)
                       ->get();

        return response()->json([
            'success' => true, 
            'jobs' => $jobs
        ]);
    }
}