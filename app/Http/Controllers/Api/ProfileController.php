<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;

class EmploymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'job_title'    => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
            'is_current'   => 'required|boolean',
        ]);

        // Save the job to the logged-in user's record
        $job = $request->user()->employmentHistories()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employment history updated!',
            'job'     => $job
        ]);
    }
}