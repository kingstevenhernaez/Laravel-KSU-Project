<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\JobPost; 

class AlumniDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 🟢 FIX: Used 'is_active' instead of 'status'
        $jobs = [];
        if (Schema::hasTable('job_posts')) {
            $jobs = DB::table('job_posts')
                        ->where('is_active', 1) // <--- This was the error source
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
        }

        // 🟢 TRACER STUDY LOGIC
        $activeSurveyId = null;
        $pendingSurveys = 0;

        if (Schema::hasTable('tracer_surveys') && Schema::hasTable('tracer_answers')) {
            $activeSurvey = DB::table('tracer_surveys')
                                ->where('status', 1) // Assuming Tracer still uses 'status'
                                ->orderBy('created_at', 'desc')
                                ->first();

            if ($activeSurvey) {
                $activeSurveyId = $activeSurvey->id;
                
                // Check if user has answered
                $hasAnswered = DB::table('tracer_answers')
                                ->where('survey_id', $activeSurvey->id)
                                ->where('user_id', $user->id)
                                ->exists();

                if (!$hasAnswered) {
                    $pendingSurveys = 1;
                }
            }
        }

        return view('alumni.dashboard', compact('user', 'jobs', 'pendingSurveys', 'activeSurveyId'));
    }

    public function jobs()
    {
        $user = Auth::user();

        // 🟢 Ensure the full job list also checks 'is_active'
        $jobs = JobPost::where('is_active', 1)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        return view('alumni.jobs', compact('user', 'jobs'));
    }
}