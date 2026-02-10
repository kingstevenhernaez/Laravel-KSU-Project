<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // 🟢 Import this to generate UUIDs
use Carbon\Carbon;

class EnrollmentSyncService
{
    public function syncStudent(array $data)
    {
        // 1. Generate Default Password
        $bdayFormatted = Carbon::parse($data['birthdate'])->format('Ymd');
        $defaultPassword = $data['student_id'] . $bdayFormatted;

        // 2. Determine Name
        $fullName = $data['first_name'] . ' ' . $data['last_name'];

        // 3. Find existing user OR create a new instance
        // We use firstOrNew so we can generate a UUID only if it's a NEW user
        $user = User::firstOrNew(['student_id' => $data['student_id']]);

        // 4. If it's a BRAND NEW user, set the one-time fields
        if (!$user->exists) {
            $user->uuid = (string) Str::uuid(); // 🟢 Generate Random UUID
            $user->password = Hash::make($defaultPassword);
            $user->force_password_change = true;
            $user->status = 1;
            $user->role = 0; // Alumni
            $user->is_alumni = true;
        }

        // 5. Update the details (Runs for both New and Existing users)
        $user->first_name = $data['first_name'];
        $user->middle_name = $data['middle_name'] ?? null;
        $user->last_name = $data['last_name'];
        $user->suffix_name = $data['suffix_name'] ?? null;
        $user->name = $fullName;
        
        $user->email = $data['email'];
        $user->mobile = $data['contact_number'] ?? null;
        $user->birthdate = $data['birthdate'];
        $user->address = $data['address'] ?? null;
        
        $user->course = $data['course'];
        $user->department = $data['department'] ?? null;
        $user->year_graduated = $data['year_graduated'];

        // 6. Save changes
        $user->save();

        return $user;
    }
}