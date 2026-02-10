<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // 🟢 Crucial Import

// 1. Landing Page
Route::get('/', function () { return view('welcome'); })->name('index'); 

// 2. Authentication
Auth::routes();

// 3. Home Redirect (Send to Admin Dashboard)
Route::get('/home', function() { return redirect()->route('admin.dashboard'); })->name('home');

// Public Directory
Route::get('/alumni-directory', [App\Http\Controllers\Frontend\AlumniDirectoryController::class, 'index'])->name('public.directory');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL (The Logic is INJECTED here to fix the errors)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 🔴 FIX 1: DASHBOARD CRASH
    // The view needs '$alumni' and '$total_users'. We query them right here.
    Route::get('/dashboard', function () {
        
        // 1. Get latest 5 users so the table doesn't crash
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->take(5)->get();
        
        // 2. Get counts for the cards
        $total_users = DB::table('users')->count();
        $total_alumni = DB::table('users')->where('is_alumni', 1)->count();
        $pending_verify = DB::table('users')->whereNull('email_verified_at')->count();

        // 3. SEND DATA TO VIEW (This fixes the "Undefined variable" error)
        return view('admin.dashboard', compact('alumni', 'total_users', 'total_alumni', 'pending_verify'));
        
    })->name('dashboard');


    // 🔴 FIX 2: ALUMNI LIST 0 RECORDS
    // We bypass the Model to ensure data shows up.
    Route::get('/alumni', function () {
        
        // Get ALL users directly from the table
        $alumni = DB::table('users')->orderBy('created_at', 'desc')->get();
        
        return view('admin.alumni.index', ['alumni' => $alumni]);

    })->name('alumni.index');


    // Other Admin Routes (Keep these standard)
    Route::get('alumni/{id}', [App\Http\Controllers\Admin\AlumniController::class, 'show'])->name('alumni.show');
    Route::delete('alumni/{id}', [App\Http\Controllers\Admin\AlumniController::class, 'destroy'])->name('alumni.destroy');
    Route::resource('jobs', App\Http\Controllers\Admin\JobController::class);
    Route::get('/tracer', [App\Http\Controllers\Admin\TracerController::class, 'index'])->name('tracer.index');
    Route::post('/tracer', [App\Http\Controllers\Admin\TracerController::class, 'store'])->name('tracer.store');
});

/*
|--------------------------------------------------------------------------
| ALUMNI PORTAL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('portal')->name('alumni.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Alumni\AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Alumni\ProfileController::class, 'index'])->name('profile');
});

// 🟢 5. CHANGE STATUS (Approve / Deactivate)
// This fixes the error in the "View Profile" page
Route::post('alumni/status/{id}', function ($id) {
    
    // 1. Find the user
    $user = DB::table('users')->where('id', $id)->first();

    if ($user) {
        // 2. Toggle Status (If 1, become 0. If 0, become 1)
        $newStatus = ($user->status == 1) ? 0 : 1;
        
        DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
    }

    // 3. Go back to the profile page
    return back()->with('success', 'Status updated successfully!');

})->name('alumni.status');