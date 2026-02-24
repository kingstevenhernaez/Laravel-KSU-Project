<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TracerController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\EventController; 
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\MisSyncController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\ProfileController;
/*
|--------------------------------------------------------------------------
| 1. DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $alumni = DB::table('users')->orderBy('created_at', 'desc')->paginate(5);
    $total_users = DB::table('users')->count();
    $total_alumni = DB::table('users')->where('role', 2)->count(); 
    $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();
    $events = [];
    return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify', 'events'));
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| 2. MIS SYNCHRONIZATION & PENDING CLAIMS
|--------------------------------------------------------------------------
*/
Route::get('/mis-sync', [MisSyncController::class, 'index'])->name('mis_sync.index');
Route::post('/mis-sync/upload', [MisSyncController::class, 'uploadCsv'])->name('mis_sync.upload');

Route::post('/mis-sync/api', [MisSyncController::class, 'syncFromApi'])->name('mis_sync.api');

// 🟢 ALTERNATIVE STRATEGY: Using a unique prefix to avoid any wildcard conflict
Route::get('/unclaimed-records', [AlumniController::class, 'pending'])->name('alumni.pending');

/*
|--------------------------------------------------------------------------
| 3. REGISTERED ALUMNI MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
Route::get('/alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');
Route::post('/alumni/status/{id}', [AlumniController::class, 'updateStatus'])->name('alumni.status');
Route::delete('/alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

// 🟢 ROLE & STAFF MANAGEMENT
Route::get('/staff', [RoleManagementController::class, 'index'])->name('roles.index');
Route::get('/staff/create', [RoleManagementController::class, 'create'])->name('roles.create');
Route::post('/staff/store', [RoleManagementController::class, 'store'])->name('roles.store');
// 🟢 NEW: Edit, Update, Delete, and Change Password Routes
Route::get('/staff/{id}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
Route::put('/staff/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
Route::delete('/staff/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
Route::put('/staff/{id}/password', [RoleManagementController::class, 'updatePassword'])->name('roles.password');
/*
|--------------------------------------------------------------------------
| 4. EVENTS MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

/*
|--------------------------------------------------------------------------
| 5. COMMUNICATION (Email Blast)
|--------------------------------------------------------------------------
*/
Route::get('/messages/create', [EmailController::class, 'index'])->name('messages.create');
Route::post('/messages/send', [EmailController::class, 'send'])->name('emails.send');
Route::get('/messages/sent', [EmailController::class, 'sentBox'])->name('messages.sent');
Route::delete('/messages/sent/{id}', [EmailController::class, 'destroy'])->name('emails.destroy');

/*
|--------------------------------------------------------------------------
| 6. JOBS & CAREERS
|--------------------------------------------------------------------------
*/
Route::resource('jobs', JobController::class);

/*
|--------------------------------------------------------------------------
| 7. TRACER STUDY
|--------------------------------------------------------------------------
*/
Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store');
Route::get('/tracer/create', [TracerController::class, 'create'])->name('tracer.create');

Route::get('/tracer/answers/{id}', [TracerController::class, 'show'])->name('tracer.answers');
Route::get('/tracer/export/{id}', [TracerController::class, 'exportAnswers'])->name('tracer.export');
Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('tracer.destroy');

/*
|--------------------------------------------------------------------------
| 8. NEWS & UPDATES
|--------------------------------------------------------------------------
*/
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
Route::post('/news/store', [NewsController::class, 'store'])->name('news.store');
Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

// 🟢 MY PROFILE & PASSWORD SETTINGS
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');