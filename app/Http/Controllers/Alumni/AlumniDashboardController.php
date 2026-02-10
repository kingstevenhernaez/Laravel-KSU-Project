<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Import Models
use App\Models\TracerSurvey;
use App\Models\TracerAnswer;
use App\Models\Job;     // <--- We need this
use App\Models\Event;   // <--- We need this
use App\Models\News;    // <--- We need this

class AlumniDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. FETCH JOBS (Show latest 5 active jobs)
        $jobs = Job::where('is_active', true)->latest()->take(5)->get();

        // 2. FETCH EVENTS & NEWS (If they exist, otherwise empty)
        $events = class_exists(Event::class) ? Event::latest()->take(3)->get() : [];
        $news   = class_exists(News::class)  ? News::latest()->take(3)->get()  : [];

        // 3. TRACER STUDY LOGIC
        $activeSurvey = TracerSurvey::where('status', 1)->latest()->first();
        $hasAnswered = false;
        $activeSurveyId = null;

        if ($activeSurvey) {
            $activeSurveyId = $activeSurvey->id;
            $hasAnswered = TracerAnswer::where('user_id', $user->id)
                                        ->where('tracer_survey_id', $activeSurvey->id)
                                        ->exists();
        }

        $pendingSurveys = ($activeSurvey && !$hasAnswered) ? 1 : 0;

        // 4. SEND TO VIEW
        return view('alumni.dashboard', compact(
            'user', 
            'jobs',      // <--- Sending jobs to the dashboard
            'events', 
            'news', 
            'pendingSurveys', 
            'activeSurveyId'
        ));
    }
}