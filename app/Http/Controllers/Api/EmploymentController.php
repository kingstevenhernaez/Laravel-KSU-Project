<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmploymentHistory; // 🟢 Ensure your model name matches this
use Illuminate\Http\Request;

class EmploymentController extends Controller
{
    /**
     * Get the employment history for the logged-in user.
     */
    public function index(Request $request)
    {
        $history = $request->user()->employmentHistories()
            ->orderBy('is_current', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return response_json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Store a new employment record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title'    => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
            'is_current'   => 'required|boolean',
        ]);

        $job = $request->user()->employmentHistories()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employment history updated!',
            'job'     => $job
        ], 201);
    }

    /**
     * Remove the specified employment record.
     */
    public function destroy($id)
    {
        // Ensure the user only deletes their own records
        $job = auth()->user()->employmentHistories()->findOrFail($id);
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.'
        ]);
    }
}