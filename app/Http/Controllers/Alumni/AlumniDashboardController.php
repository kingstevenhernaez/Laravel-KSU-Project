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
        $events = [];
        if (Schema::hasTable('events')) {
            $events = Event::where('date', '>=', now())
                           ->orderBy('date', 'asc')
                           ->take(3) 
                           ->get();
        }

        // 🟢 NEW: TRACER STUDY LOGIC (With ICT Targeting Rules Applied)
        $activeSurveyId = null;
        $pendingSurveys = 0;

        // Fetch the latest active survey that matches the alumni's specific Course and Batch
        $latestSurvey = \App\Models\Survey::where('is_active', 1)
            ->where(function ($query) use ($user) {
                // If target_course is empty, it means "All Courses". Otherwise, it must match the user's course.
                $query->whereNull('target_course')
                      ->orWhere('target_course', '')
                      ->orWhere('target_course', $user->course);
            })
            ->where(function ($query) use ($user) {
                // If target_batch is empty, it means "All Batches". Otherwise, it must match the user's batch.
                $query->whereNull('target_batch')
                      ->orWhere('target_batch', '')
                      ->orWhere('target_batch', $user->batch);
            })
            ->latest()
            ->first();

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

    public function allEvents()
    {
        $user = Auth::user();
        $events = \App\Models\Event::orderBy('date', 'desc')->paginate(12);
        return view('alumni.events.index', compact('user', 'events'));
    }

    public function jobs()
    {
        $user = Auth::user();
        $jobs = JobPost::where('is_active', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('alumni.jobs', compact('user', 'jobs'));
    }
}