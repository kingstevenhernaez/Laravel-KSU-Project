<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addon\committee\alumni\NominationController;
use App\Http\Controllers\addon\donation\frontend\DonationController;
use App\Http\Controllers\Alumni\OrderController;
use App\Http\Controllers\Api\EnrollmentSyncController;


use App\Http\Middleware\CheckEnrollmentApiKey; // 🟢 Import the Middleware

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
