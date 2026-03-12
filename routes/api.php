<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addon\committee\alumni\NominationController;
use App\Http\Controllers\addon\donation\frontend\DonationController;
use App\Http\Controllers\Alumni\OrderController;
use App\Http\Controllers\Api\EnrollmentSyncController;
use App\Http\Controllers\Api\AuthController; // 🟢 Added for Mobile App Auth

use App\Http\Middleware\CheckEnrollmentApiKey; 
use App\Models\User; 

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
    
    // 1. Fetch the Alumni and their Career Timeline
    $alumni = App\Models\User::with(['employmentHistories' => function($query) {
        $query->orderBy('start_date', 'desc'); // Orders by newest jobs first
    }])->where('student_id', $student_id)->first();

    // 2. Return 404 if student ID isn't found
    if (!$alumni) {
        return response()->json(['error' => 'No alumni record found for this Student ID.'], 404);
    }

    // 3. Return the data formatted strictly for database mapping
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


/*
|--------------------------------------------------------------------------
| MOBILE APP API ROUTES (FLUTTER)
|--------------------------------------------------------------------------
*/

// 🟢 Public Route: App Login (Generates Token)
Route::post('/login', [AuthController::class, 'login']);

// 🟢 Protected Routes: Require valid Token from the Flutter app
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Fetch Active Tracer Studies / Surveys
Route::get('/surveys', [\App\Http\Controllers\Api\SurveyController::class, 'index']);
Route::post('/employment-history', [\App\Http\Controllers\Api\ProfileController::class, 'addJob']);
// 🟢 Delete a specific job history entry
Route::delete('/employment-history/{id}', [\App\Http\Controllers\Api\EmploymentController::class, 'destroy']);
    // Fetch Job Postings for the Dashboard
    Route::get('/jobs', [\App\Http\Controllers\Api\JobController::class, 'index']);

    Route::get('/employment-history', [EmploymentController::class, 'index']);
    Route::post('/employment-history', [EmploymentController::class, 'store']);
    Route::delete('/employment-history/{id}', [EmploymentController::class, 'destroy']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    // Get the logged-in alumnus's full profile (including course & photo URL)
Route::get('/my-profile', [\App\Http\Controllers\Api\AlumniProfileController::class, 'show']);
Route::post('/profile/update', [ProfileController::class, 'update']);

});