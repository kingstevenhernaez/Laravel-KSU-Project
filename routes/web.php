<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// --- FRONTEND & ALUMNI CONTROLLERS ---
use App\Http\Controllers\Frontend\AlumniDirectoryController;
use App\Http\Controllers\Auth\ClaimAccountController;
use App\Http\Controllers\Alumni\JobApplicationController;
use App\Http\Controllers\Alumni\AlumniIDController; 
use App\Http\Controllers\Alumni\AlumniDashboardController;
use App\Http\Controllers\Alumni\ProfileController as AlumniProfileController; 
use App\Http\Controllers\Alumni\TracerController as AlumniTracerController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TracerController as AdminTracerController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\EventController; 
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\MisSyncController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

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

Route::get('/claim-account', [ClaimAccountController::class, 'index'])->name('claim.account');
Route::get('/claim-account/search', [ClaimAccountController::class, 'search'])->name('claim.search');
Route::post('/claim-account/register', [ClaimAccountController::class, 'register'])->name('claim.register');

Auth::routes();

Route::get('/home', function() { 
    if(Auth::check() && Auth::user()->role == 1) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('alumni.dashboard');
})->name('home');

Route::get('/news/{slug}', function ($slug) {
    $newsItem = News::where('slug', $slug)->firstOrFail();
    return view('news.show', compact('newsItem'));
})->name('news.show');


/*
|--------------------------------------------------------------------------
| 2. ADMIN PANEL ROUTES (FULLY UNIFIED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // 🟢 DASHBOARD
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

    // 🟢 STAFF & ROLE MANAGEMENT (Moved from admin.php)
    Route::get('/staff', [RoleManagementController::class, 'index'])->name('roles.index');
    Route::get('/staff/create', [RoleManagementController::class, 'create'])->name('roles.create');
    Route::post('/staff/store', [RoleManagementController::class, 'store'])->name('roles.store');
    Route::get('/staff/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
    Route::put('/staff/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
    Route::delete('/staff/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
    Route::put('/staff/{id}/password', [RoleManagementController::class, 'updatePassword'])->name('roles.password');

    // 🟢 MIS SYNCHRONIZATION (Moved from admin.php)
    Route::get('/mis-sync', [MisSyncController::class, 'index'])->name('mis_sync.index');
    Route::post('/mis-sync/upload', [MisSyncController::class, 'uploadCsv'])->name('mis_sync.upload');
    Route::post('/mis-sync/api', [MisSyncController::class, 'syncFromApi'])->name('mis_sync.api');

    // 🟢 EVENTS (Moved from admin.php)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    // 🟢 NEWS & UPDATES (Moved from admin.php)
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news/store', [AdminNewsController::class, 'store'])->name('news.store');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // 🟢 ADMIN PROFILE (Moved from admin.php)
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // 🟢 EMAIL CENTER
    Route::post('emails/send', [EmailController::class, 'send'])->name('emails.send');
    Route::get('email-center', [EmailController::class, 'index'])->name('emails.index');
    Route::get('email-center/create', [EmailController::class, 'create'])->name('emails.create');

    // 🟢 REPORT GENERATOR
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/api/courses', [ReportController::class, 'searchCourses'])->name('reports.api.courses');
    Route::get('/reports/api/batches', [ReportController::class, 'searchBatches'])->name('reports.api.batches');
    
    // 🟢 MASTERLIST (WITH SEARCH)
    Route::get('/alumni', function (\Illuminate\Http\Request $request) {
        $query = User::where('role', 2)->where('status', 1);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $alumni = $query->orderBy('created_at', 'desc')->paginate(10);
        $pageTitle = "Alumni Master List";
        return view('admin.alumni.index', compact('alumni', 'pageTitle'));
    })->name('alumni.index');

// 🟢 MASTERLIST & UNCLAIMED RECORDS (Pointed to Controller)
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/unclaimed-records', [AlumniController::class, 'pending'])->name('claims.index');
    
    // Status & Show Alumni
    Route::post('alumni/status/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->status = ($user->status == 1) ? 0 : 1;
        $user->save();
        $message = ($user->status == 1) ? 'Alumni approved successfully!' : 'Alumni account deactivated.';
        return redirect()->back()->with('success', $message);
    })->name('alumni.status');
    Route::get('alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

    // 🟢 JOBS
    Route::resource('jobs', JobController::class);
    Route::get('jobs/{id}/applicants', [JobController::class, 'applicants'])->name('jobs.applicants');
    Route::post('jobs/applications/{applicationId}/update', [JobController::class, 'updateApplicationStatus'])->name('jobs.application.status');

    // 🟢 TRACER STUDY
    Route::get('/tracer', [AdminTracerController::class, 'index'])->name('tracer.index');
    Route::get('/tracer/create', [AdminTracerController::class, 'create'])->name('tracer.create'); 
    Route::post('/tracer', [AdminTracerController::class, 'store'])->name('tracer.store'); 
    Route::get('/tracer/answers/{id}', [AdminTracerController::class, 'show'])->name('tracer.answers'); // Fallback for old links
    Route::get('/tracer/{id}', [AdminTracerController::class, 'show'])->name('tracer.show'); 
    Route::get('/tracer/export/{id}', [AdminTracerController::class, 'exportAnswers'])->name('tracer.export');
    Route::delete('/tracer/{id}', [AdminTracerController::class, 'destroy'])->name('tracer.destroy'); 
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
    
    Route::get('/profile', [AlumniProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [AlumniProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [AlumniProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile/photo', [AlumniProfileController::class, 'removePhoto'])->name('profile.remove_photo');

    Route::get('/tracer/{id}', [AlumniTracerController::class, 'show'])->name('tracer.show');
    Route::post('/tracer/{id}', [AlumniTracerController::class, 'store'])->name('tracer.store');
});

Route::middleware(['auth'])->post('/portal/jobs/{id}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');