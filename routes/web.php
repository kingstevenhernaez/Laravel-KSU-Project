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
use App\Models\MisAlumniRecord; 

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

// 🟢 THE FIX: We added ->name('register') so the homepage buttons don't crash!
Route::get('/register', function() {
    return redirect()->route('claim.account');
})->name('register');

Route::get('/home', function() { 
    if(!Auth::check()) {
        return redirect('/login');
    }
    
    // Redirect based on exact role
    if(Auth::user()->role == 1) {
        return redirect()->route('admin.dashboard');
    } elseif(Auth::user()->role == 3) {
        return redirect()->route('registrar.dashboard'); 
    }
    
    return redirect()->route('alumni.dashboard');
})->name('home');

Route::get('/news/{slug}', function ($slug) {
    $newsItem = News::where('slug', $slug)->firstOrFail();
    return view('news.show', compact('newsItem'));
})->name('news.show');


/*
|--------------------------------------------------------------------------
| 2. ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $verifiedCount = User::where('status', 1)->count();
        $pendingCount  = User::where('status', 0)->count(); 
        $unclaimedCount = MisAlumniRecord::where('is_claimed', false)->count(); 
        $totalUsers    = User::count();
        $recentUsers   = User::orderBy('created_at', 'desc')->take(5)->get();

        $courses = User::where('role', 2)->whereNotNull('course')->where('course', '!=', '')->distinct()->orderBy('course')->pluck('course');
        $batches = User::where('role', 2)->whereNotNull('batch')->where('batch', '!=', '')->distinct()->orderBy('batch', 'desc')->pluck('batch');

        $empQuery = User::select('employment_status', DB::raw('count(*) as total'))
            ->whereNotNull('employment_status')->where('employment_status', '!=', '');
        
        if ($request->filled('course')) { $empQuery->where('course', $request->course); }
        if ($request->filled('batch')) { $empQuery->where('batch', $request->batch); }
        
        $employmentData = $empQuery->groupBy('employment_status')->pluck('total', 'employment_status')->toArray();

        $batchData = User::select('batch', DB::raw('count(*) as total'))
            ->whereNotNull('batch')->where('batch', '!=', '')
            ->groupBy('batch')->orderBy('total', 'desc')->take(5)
            ->pluck('total', 'batch')->toArray();

        return view('admin.dashboard', compact('verifiedCount', 'pendingCount', 'unclaimedCount', 'totalUsers', 'recentUsers', 'employmentData', 'batchData', 'courses', 'batches'));
    })->name('dashboard');

    // STAFF & ROLE MANAGEMENT 
    Route::get('/staff', [RoleManagementController::class, 'index'])->name('roles.index');
    Route::get('/staff/create', [RoleManagementController::class, 'create'])->name('roles.create');
    Route::post('/staff/store', [RoleManagementController::class, 'store'])->name('roles.store');
    Route::get('/staff/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
    Route::put('/staff/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
    Route::delete('/staff/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
    Route::put('/staff/{id}/password', [RoleManagementController::class, 'updatePassword'])->name('roles.password');

    // MIS SYNCHRONIZATION 
    Route::get('/mis-sync', [MisSyncController::class, 'index'])->name('mis_sync.index');
    Route::post('/mis-sync/upload', [MisSyncController::class, 'uploadCsv'])->name('mis_sync.upload');
    Route::post('/mis-sync/api', [MisSyncController::class, 'syncFromApi'])->name('mis_sync.api');

    // EVENTS 
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    // NEWS & UPDATES 
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news/store', [AdminNewsController::class, 'store'])->name('news.store');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // ADMIN PROFILE 
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // EMAIL CENTER
    Route::post('emails/send', [EmailController::class, 'send'])->name('emails.send');
    Route::get('email-center', [EmailController::class, 'index'])->name('emails.index');
    Route::get('email-center/create', [EmailController::class, 'create'])->name('emails.create');

    // REPORT GENERATOR
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/api/courses', [ReportController::class, 'searchCourses'])->name('reports.api.courses');
    Route::get('/reports/api/batches', [ReportController::class, 'searchBatches'])->name('reports.api.batches');
    
    // MASTERLIST & UNCLAIMED RECORDS
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/unclaimed-records', [AlumniController::class, 'pending'])->name('claims.index');

    Route::post('alumni/status/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->status = ($user->status == 1) ? 0 : 1;
        $user->save();
        $message = ($user->status == 1) ? 'Alumni approved successfully!' : 'Alumni account deactivated.';
        return redirect()->back()->with('success', $message);
    })->name('alumni.status');
    Route::get('alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

    // JOBS
    Route::resource('jobs', JobController::class);
    Route::get('jobs/{id}/applicants', [JobController::class, 'applicants'])->name('jobs.applicants');
    Route::post('jobs/applications/{applicationId}/update', [JobController::class, 'updateApplicationStatus'])->name('jobs.application.status');

    // TRACER STUDY
    Route::get('/tracer', [AdminTracerController::class, 'index'])->name('tracer.index');
    Route::get('/tracer/create', [AdminTracerController::class, 'create'])->name('tracer.create'); 
    Route::post('/tracer', [AdminTracerController::class, 'store'])->name('tracer.store'); 
    Route::get('/tracer/answers/{id}', [AdminTracerController::class, 'show'])->name('tracer.answers'); 
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
    
    Route::get('/events/{id}', function ($id) {
        $event = \DB::table('events')->where('id', $id)->first();
        if (!$event) { abort(404, 'Event not found.'); }
        return view('alumni.events.show', compact('event'));
    })->name('events.show');

    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::get('/id-card', [AlumniIDController::class, 'show'])->name('id_card');
    Route::get('/jobs', [AlumniDashboardController::class, 'jobs'])->name('jobs.index'); 
    
    Route::get('/profile', [AlumniProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [AlumniProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [AlumniProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile/photo', [AlumniProfileController::class, 'removePhoto'])->name('profile.remove_photo');

    // SYSTEM A: The Original Static Tracer Routes
    Route::get('/tracer/{id}', [AlumniTracerController::class, 'show'])->name('tracer.show');
    Route::post('/tracer/{id}', [AlumniTracerController::class, 'store'])->name('tracer.store');

    Route::post('/profile/employment', [AlumniProfileController::class, 'storeEmployment'])->name('profile.employment.store');
    Route::delete('/profile/employment/{id}', [AlumniProfileController::class, 'destroyEmployment'])->name('profile.employment.destroy');

    // ALUMNI DOCUMENT REQUESTS
    Route::get('/documents', [\App\Http\Controllers\Alumni\DocumentRequestController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [\App\Http\Controllers\Alumni\DocumentRequestController::class, 'create'])->name('documents.create');
    Route::post('/documents', [\App\Http\Controllers\Alumni\DocumentRequestController::class, 'store'])->name('documents.store');
});

// Job Application Submission Route
Route::middleware(['auth'])->post('/portal/jobs/{id}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');

// SYSTEM B: The Dynamic Tracer Surveys Routes
Route::middleware(['auth'])->prefix('portal')->group(function () {
    Route::get('/tracer-surveys', function () {
        $surveys = \DB::table('surveys')->get(); 
        $submittedIds = \App\Models\SurveyAnswer::where('user_id', Auth::id())
            ->pluck('survey_id')
            ->toArray();
        return view('alumni.tracer_surveys.index', compact('surveys', 'submittedIds'));
    })->name('tracer_surveys.index');

    Route::get('/tracer-surveys/{id}', [AlumniTracerController::class, 'show'])->name('tracer_surveys.show');
    Route::post('/tracer-surveys/{id}', [AlumniTracerController::class, 'store'])->name('tracer_surveys.submit');
});

/*
|--------------------------------------------------------------------------
| 4. REGISTRAR ROUTES (ROLE 3)
|--------------------------------------------------------------------------
*/
// INTEGRATED: The necessary routes for the Registrar Dashboard
Route::middleware(['auth'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Registrar\DocumentController::class, 'index'])->name('dashboard');
    Route::post('/documents/{id}/status', [\App\Http\Controllers\Registrar\DocumentController::class, 'updateStatus'])->name('documents.status');
    Route::get('/reports', [\App\Http\Controllers\Registrar\DocumentController::class, 'reports'])->name('reports');
    Route::get('/reports/print', [\App\Http\Controllers\Registrar\DocumentController::class, 'printReport'])->name('reports.print');
});