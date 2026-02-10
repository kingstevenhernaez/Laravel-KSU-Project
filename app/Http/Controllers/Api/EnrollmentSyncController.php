<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EnrollmentSyncService; // 🟢 Use the Service

class EnrollmentSyncController extends Controller
{
    protected $syncService;

    public function __construct(EnrollmentSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

  public function syncGraduate(Request $request)
    {
        // 1. Validate (We must list EVERYTHING we want to save)
        $validated = $request->validate([
            'student_id'     => 'required',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required|email',
            'birthdate'      => 'required|date',
            'course'         => 'required',
            'year_graduated' => 'required',
            
            // 🟢 ADD THESE so Laravel doesn't throw them away:
            'contact_number' => 'required', // Required because DB 'mobile' column is not nullable
            'middle_name'    => 'nullable',
            'suffix_name'    => 'nullable',
            'address'        => 'nullable',
            'department'     => 'nullable',
        ]);

        try {
            // 2. Call the Service
            $user = $this->syncService->syncStudent($validated);

            return response()->json([
                'success' => true,
                'message' => 'Alumni Synced Successfully',
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync Error: ' . $e->getMessage()
            ], 500);
        }
    }
}