<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Alumnus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncAlumniController extends Controller
{
    public function syncGraduates()
    {
        // 1. Fetch Data
        $response = Http::withoutVerifying()->get('http://127.0.0.1:9000/api/graduates');
        $graduates = $response->json();

        if (empty($graduates)) {
            dd("ERROR: Mock API returned empty list.");
        }

        $count = 0;

        // 2. Loop & Save (IMMEDIATE SAVE - No Transactions)
        foreach ($graduates as $grad) {
            
            $idNumber = $grad['student_number'] ?? $grad['student_id'];
            $birthday = $grad['birthday_code'] ?? '01012000';
            $password = Hash::make($idNumber . $birthday);
            $uuid = (string) Str::uuid();

            // A. Create/Update User (Include TRASHED users to revive them)
            $user = User::withTrashed()->updateOrCreate(
                ['email' => $grad['email']], 
                [
                    'name'              => $grad['first_name'] . ' ' . $grad['last_name'],
                    'password'          => $password,
                    'role'              => 3, 
                    'status'            => 1,   
                    'is_alumni'         => 1,   
                    'email_verified_at' => now(),
                    'tenant_id'         => 1,
                    'uuid'              => $uuid,
                    'deleted_at'        => null, // <--- RESURRECT ZOMBIES
                ]
            );

            // B. Create Profile
            Alumnus::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id_number'       => $idNumber,
                    'batch_id'        => 1, 
                    'department_id'   => 1, 
                    'passing_year_id' => 1, 
                ]
            );

            $count++;
        }
        
        return "<h1>SYNC COMPLETE: Processed {$count} students.</h1><p>Please check the database table 'users' directly to confirm.</p>";
    }
}