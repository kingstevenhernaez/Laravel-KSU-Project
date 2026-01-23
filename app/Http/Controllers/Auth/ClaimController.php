<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Department;
use App\Models\KsuAlumniRecord;
use App\Models\PassingYear;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function show(): View
    {
        return view('auth.claim');
    }

  public function claim(Request $request): RedirectResponse
    {
        $v = Validator::make($request->all(), [
            'student_number' => ['required', 'string', 'max:64'],
        ], [
            'student_number.required' => __('KSU Student number is required.'),
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        // FIX: Force Tenant ID to 1 if the helper returns nothing
        $tenantId = getTenantId() ?? 1; 
        
        // Clean the input
        $studentNo = trim((string) $request->student_number);

        // Debugging: If this fails again, uncomment the next line to see what is happening
        // dd("Searching for Tenant: $tenantId, Student: $studentNo");

        // Find verified record
        $record = KsuAlumniRecord::where('tenant_id', $tenantId)
            ->where('student_number', $studentNo)
            ->first();

        if (!$record) {
            // TRY ONE MORE TIME without Tenant ID (Loose Search) just for testing
            $record = KsuAlumniRecord::where('student_number', $studentNo)->first();
        }

        if (!$record) {
            return back()->withErrors([
                'student_number' => __('ID Number not found in KSU records. Please ensure you are synced from the enrollment system.'),
            ])->withInput();
        }

        if ($record->claimed_user_id) {
            return back()->withErrors([
                'student_number' => __('This account has already been claimed.'),
            ]);
        }

        // ... (Rest of the logic remains the same: resolve Dept, Year, create User) ...
        
        // Resolve Department ID
        $departmentId = null;
        if ($record->department_code) {
            $dept = Department::where('tenant_id', $tenantId)->where('short_name', $record->department_code)->first();
            if ($dept) $departmentId = $dept->id;
        }

        // Resolve Graduation Year ID
        $passingYearId = null;
        if ($record->graduation_year) {
            $year = PassingYear::where('tenant_id', $tenantId)->where('name', $record->graduation_year)->first();
            if ($year) $passingYearId = $year->id;
        }

        $fullName = trim($record->first_name . ' ' . $record->last_name);

        // PASSWORD LOGIC: Birthdate + StudentID
        // Ensure birthdate is formatted YYYYMMDD
        $birthDatePart = \Carbon\Carbon::parse($record->birthdate)->format('Ymd');
        $generatedPassword = $birthDatePart . $studentNo;

        // Create or Update User account
        $user = User::where('tenant_id', $tenantId)->where('email', $record->email)->first();
        if (!$user) {
            $user = new User();
            $user->tenant_id = $tenantId;
        }

        $user->name = $fullName;
        $user->email = $record->email;
        $user->role = 2; // Alumni Role
        $user->status = 1; // Active
        $user->password = Hash::make($generatedPassword);
        $user->is_alumni = 1;
        $user->save();

        // Create formal Alumnus profile
        $alumnus = Alumni::where('tenant_id', $tenantId)->where('id_number', $studentNo)->first();
        if (!$alumnus) {
            $alumnus = new Alumni();
            $alumnus->tenant_id = $tenantId;
            $alumnus->id_number = $studentNo;
        }
        $alumnus->user_id = $user->id;
        $alumnus->save();

        // Mark record as claimed
        $record->claimed_user_id = $user->id;
        $record->claimed_at = now();
        $record->save();

        // Log the user in
        Auth::login($user);

        // Try 'dashboard' (Standard Laravel) or 'home'
return redirect()->route('dashboard')->with('success', __('Account claimed! Your Password is: ' . $generatedPassword));
    }
}