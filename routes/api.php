<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addon\committee\alumni\NominationController;
use App\Http\Controllers\addon\donation\frontend\DonationController;
use App\Http\Controllers\Alumni\OrderController;
use App\Http\Controllers\Api\EnrollmentSyncController;

use App\Http\Middleware\CheckEnrollmentApiKey; 
use App\Models\User; // 🟢 Added this so the API can fetch the Alumni data

// The Real Endpoint (Using the Class Name directly, no nickname needed)
Route::post('/v1/sync-graduate', [EnrollmentSyncController::class, 'syncGraduate'])
    ->middleware(CheckEnrollmentApiKey::class); 
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Callbacks / verification endpoints
Route::match(['GET', 'POST'], 'verify', [OrderController::class, 'verify'])->name('payment.verify');
Route::match(['GET', 'POST'], 'donation-verify', [DonationController::class, 'verify'])->name('donation-payment.verify');
Route::match(['GET', 'POST'], 'nomination-application-verify', [NominationController::class, 'verify'])->name('nomination_apply.verify');

/*
|--------------------------------------------------------------------------
| Alumni to MIS Integration API (Job History)
|--------------------------------------------------------------------------
| This endpoint allows the ICT department to securely pull an alumni's 
| career timeline to sync into their MIS Enrollment tables.
*/
Route::get('/v1/job-history/{student_id}', function (Request $request, $student_id) {
    
    // 1. SECURITY: Ensure only authorized servers can access this data
    $secretKey = 'KSU-ALUMNI-SECURE-2026'; // You will give this key to ICT
    
    if ($request->header('X-API-KEY') !== $secretKey) {
        return response()->json(['error' => 'Unauthorized Access. Invalid API Key.'], 401);
    }

    // 2. Fetch the Alumni and their Career Timeline
    $alumni = User::with(['employmentHistories' => function($query) {
        $query->orderBy('start_date', 'desc'); // Orders by newest jobs first
    }])->where('student_id', $student_id)->first();

    // 3. Return 404 if student ID isn't found
    if (!$alumni) {
        return response()->json(['error' => 'No alumni record found for this Student ID.'], 404);
    }

    // 4. Return the data formatted strictly for database mapping
    return response()->json([
        'student_id' => $alumni->student_id,
        'full_name'  => $alumni->first_name . ' ' . $alumni->last_name,
        'career_history' => $alumni->employmentHistories->map(function ($job) {
            return [
                'company_name'    => $job->company_name,
                'job_title'       => $job->job_title,
                'employment_type' => $job->employment_type,
                'start_date'      => $job->start_date ? $job->start_date->format('Y-m-d') : null,
                'end_date'        => $job->end_date ? $job->end_date->format('Y-m-d') : null,
                'is_current'      => $job->is_current ? true : false
            ];
        })
    ], 200);
});