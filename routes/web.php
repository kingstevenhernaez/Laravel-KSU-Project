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
use App\Models\News;
use App\Models\User; 

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () { 
    $news = News::latest()->take(3)->get();
    return view('welcome', compact('news')); 
})->name('index'); 

Route::get('/alumni-directory', [AlumniDirectoryController::class, 'index'])->name('public.directory');

// PUBLIC CLAIM ROUTES
Route::get('/claim-account', [\App\Http\Controllers\Auth\ClaimAccountController::class, 'index'])->name('claim.account');
Route::get('/claim-account/search', [\App\Http\Controllers\Auth\ClaimAccountController::class, 'search'])->name('claim.search');
Route::post('/claim-account/register', [\App\Http\Controllers\Auth\ClaimAccountController::class, 'register'])->name('claim.register');

Auth::routes();

// Home Redirect Logic
Route::get('/home', function() { 
    if(Auth::check() && Auth::user()->role == 1) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('alumni.dashboard');
})->name('home');

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
    Route::get('email-center/create', [EmailController::class, 'create'])->name('emails.create');
    
    // Dashboard Route
    Route::get('/dashboard', function () {
        $verifiedCount = User::where('status', 1)->count();
        $pendingCount  = User::where('status', 0)->count();
        $totalUsers    = User::count();
        $recentUsers   = User::orderBy('created_at', 'desc')->take(5)->get();

        $employmentData = User::select('employment_status', DB::raw('count(*) as total'))
            ->whereNotNull('employment_status')->where('employment_status', '!=', '')
            ->groupBy('employment_status')->pluck('total', 'employment_status')->toArray();

        $batchData = User::select('batch', DB::raw('count(*) as total'))
            ->whereNotNull('batch')->where('batch', '!=', '')
            ->groupBy('batch')->orderBy('total', 'desc')->take(5)
            ->pluck('total', 'batch')->toArray();

        return view('admin.dashboard', compact('verifiedCount', 'pendingCount', 'totalUsers', 'recentUsers', 'employmentData', 'batchData'));
    })->name('dashboard');
    
    // 🟢 NEW: Pending Claims Route (Reuses Alumni view but filters status = 0)
    Route::get('/pending-claims', function () {
        $alumni = User::where('role', 2)->where('status', 0)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    })->name('claims.index');

    // Masterlist Routes
    Route::get('/masterlist', function () {
        $alumni = User::where('role', 2)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    })->name('masterlist');

    Route::get('/alumni', function () {
        $alumni = User::where('role', 2)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    })->name('alumni.index');

    Route::post('alumni/status/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->status = ($user->status == 1) ? 0 : 1;
        $user->save();
        $message = ($user->status == 1) ? 'Alumni approved successfully!' : 'Alumni account deactivated.';
        return redirect()->back()->with('success', $message);
    })->name('alumni.status');

    Route::get('alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

    Route::resource('jobs', JobController::class);

    // Tracer Study Routes
    Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
    Route::get('/tracer/create', [TracerController::class, 'create'])->name('tracer.create'); 
    Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store'); 
    Route::get('/tracer/{id}', [TracerController::class, 'show'])->name('tracer.show'); 
    Route::get('/tracer/export/{id}', [TracerController::class, 'exportAnswers'])->name('tracer.export');
    Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('tracer.destroy'); 

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

    Route::get('/tracer/{id}', [\App\Http\Controllers\Alumni\TracerController::class, 'show'])->name('tracer.show');
    Route::post('/tracer/{id}', [\App\Http\Controllers\Alumni\TracerController::class, 'store'])->name('tracer.store');
});

Route::middleware(['auth'])
     ->post('/portal/jobs/{id}/apply', [JobApplicationController::class, 'apply'])
     ->name('jobs.apply');