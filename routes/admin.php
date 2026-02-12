<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TracerController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\EventController; // 🟢 Moved to top

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (admin.php)
|--------------------------------------------------------------------------
*/

// 🟢 1. DASHBOARD (Inline Logic Preserved)
Route::get('/dashboard', function () {
    $alumni = DB::table('users')->orderBy('created_at', 'desc')->paginate(5);
    $total_users = DB::table('users')->count();
    $total_alumni = DB::table('users')->where('is_alumni', 1)->count(); // Check if column is 'is_alumni' or just 'role'
    $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();

    return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify'));
})->name('dashboard');

// 🟢 2. ALUMNI MANAGEMENT
// List View (Inline Logic Preserved)
Route::get('/alumni', function () {
    $alumni = DB::table('users')
                ->where('role', '!=', 1) 
                ->orderBy('created_at', 'desc')
                ->get();
    return view('admin.alumni.index', ['alumni' => $alumni]);
})->name('alumni.index');

// View Profile (Inline Logic Preserved)
Route::get('alumni/{id}', function ($id) {
    $user = DB::table('users')->where('id', $id)->first();
    if (!$user) {
        return redirect()->route('admin.alumni.index')->with('error', 'User not found');
    }
    return view('admin.alumni.show', ['user' => $user]);
})->name('alumni.show');

// Change Status (Inline Logic Preserved)
Route::post('/alumni/status/{id}', function ($id) {
    $user = DB::table('users')->where('id', $id)->first();
    if ($user) {
        $newStatus = ($user->status == 1) ? 0 : 1;
        DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
    }
    return back()->with('success', 'Status updated successfully!');
})->name('alumni.status');

// Delete Alumni (Uses Controller)
Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');


// 🟢 3. EVENTS MANAGEMENT (Uses Controller)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');


// 🟢 4. COMMUNICATION (Email Blast)
Route::get('/messages/create', [EmailController::class, 'index'])->name('messages.create');
Route::post('/messages/send', [EmailController::class, 'send'])->name('emails.send');
Route::get('/messages/sent', [EmailController::class, 'sentBox'])->name('messages.sent');
Route::delete('/messages/sent/{id}', [EmailController::class, 'destroy'])->name('emails.destroy');


// 🟢 5. JOBS & CAREERS
Route::resource('jobs', JobController::class);


// 🟢 6. TRACER STUDY
Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store');
Route::get('/tracer/answers/{id}', [TracerController::class, 'show'])->name('tracer.answers');
Route::get('/tracer/export/{id}', [TracerController::class, 'exportAnswers'])->name('tracer.export');
Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('tracer.destroy');


// 🟢 7. NEWS (Placeholder)
Route::get('/news', function () { return "News Page Coming Soon"; })->name('news.index');