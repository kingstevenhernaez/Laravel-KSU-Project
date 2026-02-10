<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AlumniIDController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // 🟢 GENERATE UNIQUE ID
        // Format: KSU-[BATCH]-[USER_ID] (e.g., KSU-2023-0058)
        // We use the User's unique database ID ($user->id) to ensure uniqueness.
        
        // Use '0000' as a fallback if the batch is not set
        $batch = $user->batch ? $user->batch : '0000';
        // Pad the user ID with zeros to make it 4 digits (e.g., 5 becomes 0005)
        $userIdPadded = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        
        $alumniIdNumber = "KSU-{$batch}-{$userIdPadded}";

        return view('alumni.id-card', compact('user', 'alumniIdNumber'));
    }
}