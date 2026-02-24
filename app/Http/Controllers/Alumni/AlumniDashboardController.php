<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\JobPost; 
use App\Models\Event; 

class AlumniDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // JOB LOGIC
        $jobs = [];
        if (Schema::hasTable('job_posts')) {
            $jobs = DB::table('job_posts')
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
        }

        // EVENT LOGIC
        // We only show events that are scheduled for today or in the future
        $events = [];
        if (Schema::hasTable('events')) {
            $events = Event::where('date', '>=', now())
                           ->orderBy('date', 'asc')
                           ->take(3) // Shows the next 3 upcoming events
                           ->get();
        }

        // 🟢 NEW: TRACER STUDY LOGIC (Dynamic Surveys)
        $activeSurveyId = null;
        $pendingSurveys = 0;

        // Fetch the latest active survey from the new 'surveys' table
        $latestSurvey = \App\Models\Survey::where('is_active', 1)->latest()->first();

        if ($latestSurvey) {
            // Check if the current user has answered it
            $hasAnswered = \App\Models\SurveyAnswer::where('survey_id', $latestSurvey->id)
                                                 ->where('user_id', $user->id)
                                                 ->exists();
            
            if (!$hasAnswered) {
                $pendingSurveys = 1;
                $activeSurveyId = $latestSurvey->id;
            }
        }

        return view('alumni.dashboard', compact('user', 'jobs', 'pendingSurveys', 'activeSurveyId', 'events'));
    }

    /**
     * Dedicated page to view all events
     */
   public function allEvents()
    {
        $user = Auth::user();
        // Fetch all events, paginated
        $events = \App\Models\Event::orderBy('date', 'desc')->paginate(12);
        
        return view('alumni.events.index', compact('user', 'events'));
    }

    public function jobs()
    {
        $user = Auth::user();
        $jobs = JobPost::where('is_active', 1)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        return view('alumni.jobs', compact('user', 'jobs'));
    }
}