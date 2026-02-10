<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TracerController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (admin.php)
|--------------------------------------------------------------------------
*/

// 🟢 1. DASHBOARD (Fixed: Pagination & Analytics)
Route::get('/dashboard', function () {
    $alumni = DB::table('users')->orderBy('created_at', 'desc')->paginate(5);
    
    $total_users = DB::table('users')->count();
    $total_alumni = DB::table('users')->where('is_alumni', 1)->count();
    $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();

    return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify'));
})->name('dashboard');


// 🟢 2. ALUMNI LIST (Fixed: Hides Admin & Shows Data)
Route::get('/alumni', function () {
    $alumni = DB::table('users')
                ->where('role', '!=', 1) // Hide Admin
                ->orderBy('created_at', 'desc')
                ->get();
    return view('admin.alumni.index', ['alumni' => $alumni]);
})->name('alumni.index');


// 🟢 3. CHANGE STATUS (The Missing Fix for your Error)
Route::post('/alumni/status/{id}', function ($id) {
    
    // Find user
    $user = DB::table('users')->where('id', $id)->first();
    
    if ($user) {
        // Toggle Status (1 -> 0, 0 -> 1)
        $newStatus = ($user->status == 1) ? 0 : 1;
        DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
    }
    
    return back()->with('success', 'Status updated successfully!');

})->name('alumni.status');


// 🟢 4. VIEW PROFILE (Fixed: Sends '$user' variable)
Route::get('alumni/{id}', function ($id) {
    $user = DB::table('users')->where('id', $id)->first();
    
    if (!$user) {
        return redirect()->route('admin.alumni.index')->with('error', 'User not found');
    }
    return view('admin.alumni.show', ['user' => $user]);
})->name('alumni.show');


// 🟢 5. SIDEBAR HELPERS (Prevents Crashes)
Route::get('/events', function () { return "Events Page Coming Soon"; })->name('events.index');
Route::get('/news', function () { return "News Page Coming Soon"; })->name('news.index');
Route::get('/messages/create', function () { return "Email Blast Page Coming Soon"; })->name('messages.create');


// 🟢 6. STANDARD RESOURCES
Route::resource('jobs', JobController::class);
Route::delete('alumni/{id}', [AlumniController::class, 'destroy'])->name('alumni.destroy');

// Tracer
Route::get('/tracer', [TracerController::class, 'index'])->name('tracer.index');
Route::post('/tracer', [TracerController::class, 'store'])->name('tracer.store');
Route::get('/tracer/answers/{id}', [TracerController::class, 'show'])->name('tracer.answers');
Route::get('/tracer/export/{id}', [TracerController::class, 'exportAnswers'])->name('tracer.export');
Route::delete('/tracer/{id}', [TracerController::class, 'destroy'])->name('tracer.destroy');