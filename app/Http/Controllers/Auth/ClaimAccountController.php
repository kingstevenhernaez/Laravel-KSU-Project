<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MisAlumniRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class ClaimAccountController extends Controller
{
    // 1. Show the Initial Search Form
    public function index()
    {
        return view('auth.claim');
    }

    // 2. Perform the Search (GET Request)
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'birthdate'  => 'required|date'
        ]);

        // 🟢 FIX 1: Only search by Student ID first to bypass strict date checks
        $record = MisAlumniRecord::where('student_id', $request->student_id)->first();

        if (!$record) {
            return redirect()->route('claim.account')->with('error', 'No matching record found in the MIS database. Please check your details.');
        }

        // 🟢 FIX 2: If the database HAS a date, make sure it matches. 
        if ($record->birthdate !== null && $record->birthdate !== $request->birthdate) {
            return redirect()->route('claim.account')->with('error', 'The birthdate does not match our records.');
        }

        if ($record->is_claimed) {
            return redirect()->route('login')->with('error', 'This account has already been claimed. Please log in.');
        }

        // 🟢 FIX 3: If the database date was NULL, save the real date the user just typed!
        if ($record->birthdate === null) {
            $record->update(['birthdate' => $request->birthdate]);
        }

        // If found (and date passes), show the registration form!
        return view('auth.claim_register', compact('record'));
    }

    // 3. Finalize Registration (POST Request)
   public function register(Request $request)
    {
        $request->validate([
            'record_id' => 'required|exists:mis_alumni_records,id',
            'email'     => 'required|email|unique:users,email',
            'mobile'    => 'required|string|unique:users,mobile',
            'password'  => 'required|min:8|confirmed',
            // Single File Validation
            'valid_id'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', 
        ]);

        $record = MisAlumniRecord::findOrFail($request->record_id);

        if ($record->is_claimed) {
            return redirect()->route('login')->with('error', 'Account already claimed.');
        }

        // Handle the single ID Upload
        $idPath = null;
        if ($request->hasFile('valid_id')) {
            $idPath = $request->file('valid_id')->store('alumni_ids', 'public');
        }

        // Create the User
        $user = User::create([
            'uuid'           => Str::uuid(), 
            'student_id'     => $record->student_id,
            'first_name'     => $record->first_name,
            'last_name'      => $record->last_name,
            'name'           => $record->first_name . ' ' . $record->last_name,
            'email'          => $request->email,
            'mobile'         => $request->mobile,
            'password'       => Hash::make($request->password),
            'birthdate'      => $record->birthdate, // This now safely pulls the correct date!
            'course'         => $record->course,
            'year_graduated' => $record->year_graduated,
            'role'           => 2, // Alumni
            'is_alumni'      => 1,
            'status'         => 1, // Active
            'valid_id_path'  => $idPath, // Save ID Path
        ]);

        $user->assignRole('alumni');

        $record->update([
            'is_claimed' => true,
            'user_id'    => $user->id
        ]);

        // Trigger Laravel's built-in email verification system
        event(new Registered($user));
        
        Auth::login($user);
        
        // Redirect them to a verification notice page (Laravel handles this natively)
        return redirect()->route('verification.notice')->with('success', 'Account registered! Please check your email to verify your account.');
    }
}
