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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'student_number.required' => __('Student number is required.'),
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $tenantId = getTenantId();
        $studentNo = trim((string) $request->student_number);

        $record = KsuAlumniRecord::where('tenant_id', $tenantId)
            ->where('student_number', $studentNo)
            ->first();

        if (!$record) {
            return back()->withErrors([
                'student_number' => __('Not in alumni records. Please contact the Alumni Center.'),
            ])->withInput();
        }

        // Resolve Department (Program)
        $deptName = $record->program_name ?: ($record->program_code ?: null);
        $departmentId = null;
        if ($deptName) {
            $department = Department::firstOrCreate(
                ['tenant_id' => $tenantId, 'name' => $deptName],
                ['short_name' => substr(preg_replace('/\s+/', '', $deptName), 0, 20)]
            );
            $departmentId = $department->id;
        }

        // Resolve Passing Year
        $passingYearId = null;
        if ($record->graduation_year) {
            $py = PassingYear::where('tenant_id', $tenantId)
                ->where('name', (string) $record->graduation_year)
                ->first();
            if (!$py) {
                $py = new PassingYear();
                $py->tenant_id = $tenantId;
                $py->name = (string) $record->graduation_year;
                $py->save();
            }
            $passingYearId = $py->id;
        }

        $fullName = trim(implode(' ', array_filter([
            $record->first_name,
            $record->middle_name,
            $record->last_name,
        ])));
        if ($fullName === '') {
            $fullName = $studentNo;
        }

        // Create or update a user account (email is optional)
        $user = null;
        if ($record->email) {
            $user = User::where('tenant_id', $tenantId)->where('email', $record->email)->first();
        }
        if (!$user) {
            // If no email match, try by phone
            if ($record->mobile) {
                $user = User::where('tenant_id', $tenantId)->where('mobile', $record->mobile)->first();
            }
        }
        if (!$user) {
            $user = new User();
            $user->tenant_id = $tenantId;
        }

        $user->name = $fullName;
        $user->email = $record->email ?: null;
        $user->mobile = $record->mobile ?: null;
        $user->role = USER_ROLE_ALUMNI;
        $user->status = STATUS_ACTIVE;
        $user->password = Hash::make((string) $request->password);
        $user->is_alumni = STATUS_ACTIVE;
        $user->save();

        // Ensure alumnus record exists
        $alumnus = Alumni::where('tenant_id', $tenantId)
            ->where('id_number', $studentNo)
            ->first();
        if (!$alumnus) {
            $alumnus = new Alumni();
            $alumnus->tenant_id = $tenantId;
            $alumnus->id_number = $studentNo;
        }
        $alumnus->user_id = $user->id;
        if ($departmentId) {
            $alumnus->department_id = $departmentId;
        }
        if ($passingYearId) {
            $alumnus->passing_year_id = $passingYearId;
        }
        $alumnus->save();

        // Mark synced record as claimed
        // Claim fields are optional; if the DB columns exist (after migration), store claim info.
        try {
            $record->claimed_user_id = $user->id;
            $record->claimed_at = now();
            $record->save();
        } catch (\Throwable $e) {
            // ignore if columns not present yet
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', __('Your alumni account has been activated.'));
    }
}
