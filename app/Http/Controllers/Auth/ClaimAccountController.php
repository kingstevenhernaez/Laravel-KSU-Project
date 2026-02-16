<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MisAlumniRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $record = MisAlumniRecord::where('student_id', $request->student_id)
                                 ->where('birthdate', $request->birthdate)
                                 ->first();

        if (!$record) {
            return redirect()->route('claim.account')->with('error', 'No matching record found in the MIS database. Please check your details.');
        }

        if ($record->is_claimed) {
            return redirect()->route('login')->with('error', 'This account has already been claimed. Please log in.');
        }

        // 🟢 If found, show the registration form!
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
        ]);

        $record = MisAlumniRecord::findOrFail($request->record_id);

        if ($record->is_claimed) {
            return redirect()->route('login')->with('error', 'Account already claimed.');
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
            'birthdate'      => $record->birthdate,
            'course'         => $record->course,
            'year_graduated' => $record->year_graduated,
            'role'           => 2, // Alumni
            'is_alumni'      => 1,
            'status'         => 1, // Active
        ]);

        $user->assignRole('alumni');

        $record->update([
            'is_claimed' => true,
            'user_id'    => $user->id
        ]);

        Auth::login($user);
        
        return redirect()->route('alumni.dashboard')->with('success', 'Account claimed successfully! Welcome back.');
    }
}