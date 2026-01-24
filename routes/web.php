<?php

use App\Http\Controllers\Alumni\GoogleAuthController;
use App\Http\Controllers\Auth\ClaimController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PublicAlumniDirectoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::any('/install{any?}', function () {
    abort(404);
})->where('any', '.*');

Route::any('/version-update{any?}', function () {
    abort(404);
})->where('any', '.*');

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('iso_code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->iso_code;
        }
    }

    session()->put('local', $ln);
    return redirect()->back();
})->name('local');

/**
 * Auth
 * Public registration is disabled. Alumni must claim their account from synced alumni records.
 */
Auth::routes(['verify' => false, 'register' => false]);

// Change 'claim' to 'claim.show' to fix the login page error
Route::get('claim', [ClaimController::class, 'show'])->name('claim.show');

// Keep this one as is
Route::post('claim', [ClaimController::class, 'claim'])->name('claim.post');

Route::get('password/reset/verify/{token}/{email}', [ForgotPasswordController::class, 'forgetVerifyForm'])
    ->name('password.reset.verify_form');
Route::get('password/reset/verify/{token}', [ForgotPasswordController::class, 'forgetVerify'])
    ->name('password.reset.verify');
Route::post('password/reset/verify-resend/{token}', [ForgotPasswordController::class, 'forgetVerifyResend'])
    ->name('password.reset.verify_resend');
Route::post('password/reset/update/{token}', [ForgotPasswordController::class, 'updatePassword'])
    // Custom reset endpoint used by this project (token is in the URL)
    // Must NOT conflict with Laravel's default named route: password.update
    ->name('password.update.custom');

Route::middleware(['auth'])->group(function () {
    Route::get('google2fa/authenticate/verify', [GoogleAuthController::class, 'verifyView'])
        ->name('google2fa.authenticate.verify');
    Route::post('google2fa/authenticate/verify/action', [GoogleAuthController::class, 'verify'])
        ->name('google2fa.authenticate.verify.action');
    Route::post('google2fa/authenticate/enable', [GoogleAuthController::class, 'enable'])
        ->name('google2fa.authenticate.enable');
    Route::post('google2fa/authenticate/disable', [GoogleAuthController::class, 'disable'])
        ->name('google2fa.authenticate.disable');

});

/**
 * Social login
 */
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google-login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook-login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

/**
 * Load other route files used by this project (if they exist).
 * This keeps Event Management, Member Management, Admin, Pages, Tools, etc. intact.
 */


$adminRoutes = __DIR__ . '/admin.php';
if (file_exists($adminRoutes)) {
    require $adminRoutes;
}

$alumniRoutes = __DIR__ . '/alumni.php';
if (file_exists($alumniRoutes)) {
    require $alumniRoutes;
}

Route::get('/alumni-directory', [PublicAlumniDirectoryController::class, 'index'])
    ->name('public.alumni.directory');
    
$frontendRoutes = __DIR__ . '/frontend.php';
if (file_exists($frontendRoutes)) {
    require $frontendRoutes;
}

// --- SAFE DEBUG ROUTE (No Imports Needed) ---
\Illuminate\Support\Facades\Route::get('/debug-connection', function () {
    // 1. Read settings from .env
    $baseUrl = rtrim(env('KSU_ENROLLMENT_API_BASE_URL'), '/');
    $url = $baseUrl . '/graduates';
    $key = env('KSU_ENROLLMENT_API_KEY');

    echo "<h1>🔍 Connection Diagnostic</h1>";
    echo "<p><b>Target URL:</b> <code>{$url}</code></p>";
    echo "<p><b>API Key:</b> <code>{$key}</code></p>";
    echo "<hr>";

    try {
        // 2. Try to Connect
        $response = \Illuminate\Support\Facades\Http::withHeaders(['X-API-KEY' => $key])
            ->timeout(10)
            ->get($url);

        echo "<h3>✅ Server Responded (Status: {$response->status()})</h3>";
        
        // 3. Dump the Raw Output
        echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc;'>";
        echo htmlspecialchars($response->body());
        echo "</pre>";

    } catch (\Exception $e) {
        echo "<h3 style='color:red'>❌ Connection Error</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
});