<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Survey;
use App\Models\SurveyAnswer;

class TracerController extends Controller
{
    // View the active survey questions
    public function show($id)
    {
        // 1. Fetch the survey and its questions
        $survey = Survey::with('questions')->findOrFail($id);

        // 2. Check if the user has already answered this survey
        $hasAnswered = SurveyAnswer::where('survey_id', $id)
                                   ->where('user_id', Auth::id())
                                   ->exists();

        // 3. If already answered, prevent them from answering again
        if ($hasAnswered) {
            return redirect()->route('alumni.dashboard')->with('success', 'You have already completed this Tracer Study. Thank you!');
        }

        return view('alumni.tracer.show', compact('survey'));
    }

    // Process and save the alumni's answers
    public function store(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $userId = Auth::id();

        // Ensure they haven't submitted already (double-submit protection)
        if (SurveyAnswer::where('survey_id', $id)->where('user_id', $userId)->exists()) {
            return redirect()->route('alumni.dashboard')->with('error', 'Survey already submitted.');
        }

        // Loop through the submitted answers and save them
        // The form will send data as an array: answers[question_id] = "their answer"
        if ($request->has('answers')) {
            foreach ($request->answers as $questionId => $answerData) {
                
                // If it's a checkbox array, convert to comma-separated string
                if (is_array($answerData)) {
                    $answerText = implode(', ', $answerData);
                } else {
                    $answerText = $answerData;
                }

                if (!empty($answerText)) {
                    SurveyAnswer::create([
                        'survey_id'   => $survey->id,
                        'question_id' => $questionId,
                        'user_id'     => $userId,
                        'answer_text' => $answerText
                    ]);
                }
            }
        }

        return redirect()->route('alumni.dashboard')->with('success', 'Thank you for updating your Tracer Study information!');
    }
}