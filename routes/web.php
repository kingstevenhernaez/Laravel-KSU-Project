<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Controller Imports
use App\Http\Controllers\Alumni\JobApplicationController;
use App\Http\Controllers\Alumni\AlumniIDController; 
use App\Http\Controllers\Alumni\AlumniDashboardController;
use App\Http\Controllers\Alumni\ProfileController; 
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TracerController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Frontend\AlumniDirectoryController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('welcome'); })->name('index'); 
Route::get('/alumni-directory', [AlumniDirectoryController::class, 'index'])->name('public.directory');

Auth::routes();

// Home Redirect Logic
Route::get('/home', function() { 
    if(Auth::check() && Auth::user()->role == 1) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('alumni.dashboard');
})->name('home');


/*
|--------------------------------------------------------------------------
| 2. ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

Route::get('email-center', [EmailController::class, 'index'])->name('admin.emails.index');
Route::post('email-center/send', [EmailController::class, 'send'])->name('admin.emails.send');

    Route::get('/dashboard', function () {
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->take(5)->get();
        $total_users = DB::table('users')->count();
        $total_alumni = DB::table('users')->where('role', 2)->count(); 
        $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();

        return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify'));
    })->name('dashboard');

    Route::get('/alumni', function () {
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->get();
        return view('admin.alumni.index', ['alumni' => $alumni]);
    })->name('alumni.index');

    Route::get('alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

    Route::post('alumni/status/{id}', function ($id) {
        $user = DB::table('users')->where('id', $id)->first();
        if ($user) {
            $newStatus = ($user->status == 1) ? 0 : 1;
            DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
        }
        return back()->with('success', 'Status updated successfully!');
    })->name('alumni.status');

    Route::resource('jobs', JobController::class);
    Route::get('jobs/{id}/applicants', [JobController::class, 'applicants'])->name('jobs.applicants');

    Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
    Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store');
    Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('
        tracer.destroy');

    // 🟢 NEW: View Applicants for a specific job
    Route::get('jobs/{id}/applicants', [JobController::class, 'applicants'])->name('jobs.applicants');
    
    // 🟢 NEW: Update Application Status (e.g., Pending -> Hired)
    Route::post('jobs/applications/{applicationId}/update', [JobController::class, 'updateApplicationStatus'])
         ->name('jobs.application.status');
});


/*
|--------------------------------------------------------------------------
| 3. ALUMNI PORTAL ROUTES
|--------------------------------------------------------------------------
*/

// 🟢 ALUMNI DASHBOARD & PROFILE (Prefix: /portal, Name: alumni.)
Route::middleware(['auth'])->prefix('portal')->name('alumni.')->group(function () {
    
    // URL: /portal/dashboard  -> Name: alumni.dashboard
    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    
    // URL: /portal/id-card    -> Name: alumni.id_card
    Route::get('/id-card', [AlumniIDController::class, 'show'])->name('id_card');

    // URL: /portal/jobs       -> Name: alumni.jobs.index
    Route::get('/jobs', [AlumniDashboardController::class, 'jobs'])->name('jobs.index'); 
    
    // URL: /portal/profile    -> Name: alumni.profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // URL: /portal/profile/update -> Name: alumni.profile.update
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // URL: /portal/profile/password -> Name: alumni.profile.password
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// 🟢 JOB APPLICATION ROUTE (Kept separate/outside the group to avoid double prefixing)
// This matches exactly what is in your View form: route('jobs.apply')
Route::middleware(['auth'])
     ->post('/portal/jobs/{id}/apply', [JobApplicationController::class, 'apply'])
     ->name('jobs.apply');