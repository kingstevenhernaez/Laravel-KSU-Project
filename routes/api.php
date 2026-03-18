<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Existing Web Imports
use App\Http\Controllers\addon\committee\alumni\NominationController;
use App\Http\Controllers\addon\donation\frontend\DonationController;
use App\Http\Controllers\Alumni\OrderController;
use App\Http\Controllers\Api\EnrollmentSyncController;
use App\Http\Middleware\CheckEnrollmentApiKey; 
use App\Models\User; 

// 🟢 ALL MOBILE API CONTROLLERS MUST BE IMPORTED HERE!
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmploymentController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\AlumniProfileController;


// The Real Endpoint 
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
*/
Route::get('/v1/job-history/{student_id}', function (Request $request, $student_id) {
    $alumni = App\Models\User::with(['employmentHistories' => function($query) {
        $query->orderBy('start_date', 'desc'); 
    }])->where('student_id', $student_id)->first();

    if (!$alumni) {
        return response()->json(['error' => 'No alumni record found for this Student ID.'], 404);
    }

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

// Public Route: App Login
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes: Require valid Token from Flutter
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboards
    Route::get('/surveys', [SurveyController::class, 'index']);
    Route::get('/jobs', [JobController::class, 'index']);

    // 🟢 Fetch Upcoming Events for the Mobile App
    Route::get('/events', function () {
        // Make sure your database table is actually named 'events' and has 'title' and 'date' columns
        $events = \DB::table('events')
            ->where('date', '>=', now()) // Only show future events
            ->orderBy('date', 'asc')
            ->take(5) // Send the next 5 events
            ->get();
            
        return response()->json([
            'events' => $events
        ]);
    });

    // Employment History (Duplicates Removed!)
    Route::get('/employment-history', [EmploymentController::class, 'index']);
    Route::post('/employment-history', [EmploymentController::class, 'store']);
    Route::delete('/employment-history/{id}', [EmploymentController::class, 'destroy']);
    
    // Profile & ID Card Integration
    Route::get('/my-profile', [AlumniProfileController::class, 'show']);
    Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
});