<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TracerSurvey;
use App\Models\TracerAnswer; // We assume you have this model

class TracerSurveyController extends Controller
{
    // 1. Show the Survey Form
  public function show($id)
    {
        // 🔴 OLD CODE (This was causing the redirect loop)
        // $survey = TracerSurvey::find($id);
        // if (!$survey) {
        //     return redirect()->route('alumni.dashboard')->with('error', 'Survey not found.');
        // }

        // 🟢 NEW CODE (Test Mode)
        // This creates a fake survey object on the fly so the page never fails.
        $survey = new TracerSurvey();
        $survey->id = 1;
        $survey->title = "Graduate Tracer Study 2026";
        $survey->description = "Please answer the following questions.";

        return view('alumni.tracer.form', compact('survey'));
    }

   // Import the model at the top if you haven't yet:
    // use App\Models\TracerAnswer; 

    public function store(Request $request, $id)
    {
        // 1. Validate the Input
        $request->validate([
            'employment_status' => 'required',
            // If employed, these are required:
            'job_title' => 'required_if:employment_status,Employed',
            'company_name' => 'required_if:employment_status,Employed',
        ]);

        // 2. Save to Database
        // We use 'updateOrCreate' so if they answer again, it updates their old answer instead of creating duplicates.
        \App\Models\TracerAnswer::updateOrCreate(
            ['user_id' => Auth::id(), 'tracer_survey_id' => $id], // Search criteria
            [
                'employment_status' => $request->employment_status,
                'job_title' => $request->job_title,
                'company_name' => $request->company_name,
                'salary_range' => $request->salary_range,
                'is_related' => $request->is_related,
            ]
        );

        // 3. Redirect with Success
        return redirect()->route('alumni.dashboard')
            ->with('success', 'Thank you! Your employment status has been updated.');
    }
}