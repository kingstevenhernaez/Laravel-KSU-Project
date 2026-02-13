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
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Frontend\AlumniDirectoryController;
use App\Models\News; // 🟢 ADDED: Import News Model

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// 🟢 MODIFIED: Homepage now fetches News
Route::get('/', function () { 
    // Fetch latest 3 news items
    $news = News::latest()->take(3)->get();
    
    return view('welcome', compact('news')); 
})->name('index'); 

Route::get('/alumni-directory', [AlumniDirectoryController::class, 'index'])->name('public.directory');

Auth::routes();

// Home Redirect Logic
Route::get('/home', function() { 
    if(Auth::check() && Auth::user()->role == 1) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('alumni.dashboard');
})->name('home');

// Route to view a single news article
Route::get('/news/{slug}', function ($slug) {
    $newsItem = App\Models\News::where('slug', $slug)->firstOrFail();
    return view('news.show', compact('newsItem'));
})->name('news.show');

/*
|--------------------------------------------------------------------------
| 2. ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Email Center Routes
    Route::post('emails/send', [EmailController::class, 'send'])->name('emails.send');
    Route::get('email-center', [EmailController::class, 'index'])->name('emails.index');

    Route::get('/dashboard', function () {
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->take(5)->get();
        $total_users = DB::table('users')->count();
        $total_alumni = DB::table('users')->where('role', 2)->count(); 
        $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();

        // Pass empty events array to prevent dashboard crash if widget exists
        $events = [];

        return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify', 'events'));
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

    Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
    Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store');
    Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('tracer.destroy');

    // Job Applicants Logic
    Route::get('jobs/{id}/applicants', [JobController::class, 'applicants'])->name('jobs.applicants');
    Route::post('jobs/applications/{applicationId}/update', [JobController::class, 'updateApplicationStatus'])
         ->name('jobs.application.status');
});


/*
|--------------------------------------------------------------------------
| 3. ALUMNI PORTAL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('portal')->name('alumni.')->group(function () {
    
    Route::get('/events', [AlumniDashboardController::class, 'allEvents'])->name('events');
    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::get('/id-card', [AlumniIDController::class, 'show'])->name('id_card');
    Route::get('/jobs', [AlumniDashboardController::class, 'jobs'])->name('jobs.index'); 
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

Route::middleware(['auth'])
     ->post('/portal/jobs/{id}/apply', [JobApplicationController::class, 'apply'])
     ->name('jobs.apply');



