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
        $user = Auth::user();

        // 1. Fetch the survey and its questions
        $survey = Survey::with('questions')->findOrFail($id);

        $courseMismatch = !empty($survey->target_course) && $survey->target_course != $user->course;
        $batchMismatch = !empty($survey->target_batch) && $survey->target_batch != $user->batch;

        if ($courseMismatch || $batchMismatch) {
            return redirect()->route('alumni.dashboard')->with('error', 'This specific survey is not intended for your program or batch.');
        }

        // 2. THE FIX: Fetch their previous answers from the database!
        // This creates an array where the key is the Question ID, and the value is their Answer.
        $previousAnswers = SurveyAnswer::where('survey_id', $id)
                                   ->where('user_id', $user->id)
                                   ->pluck('answer_text', 'question_id')
                                   ->toArray();

        // 3. Check if they have answered it based on whether we found data
        $hasAnswered = !empty($previousAnswers);

        // 4. DO NOT redirect. Pass the saved answers to your beautiful form instead!
        return view('alumni.tracer.show', compact('survey', 'hasAnswered', 'previousAnswers'));
    }

    // Process and save the alumni's answers
    public function store(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $userId = Auth::id();

        if (SurveyAnswer::where('survey_id', $id)->where('user_id', $userId)->exists()) {
            return redirect()->route('tracer_surveys.index')->with('error', 'Survey already submitted.');
        }

        if ($request->has('answers')) {
            foreach ($request->answers as $questionId => $answerData) {
                $answerText = is_array($answerData) ? implode(', ', $answerData) : $answerData;

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

        return redirect()->route('tracer_surveys.index')->with('success', 'Thank you for updating your Tracer Study information!');
    }
}