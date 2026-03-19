<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyAnswer;

class SurveyController extends Controller
{
 // Fetches the list of surveys for the mobile dashboard
    public function index(Request $request)
    {
        $user = $request->user();
        
        // 1. Fetch ALL active surveys first
        $allSurveys = Survey::where('is_active', 1)->latest()->get();
        
        // 2. Safely filter them using PHP 
        $filteredSurveys = $allSurveys->filter(function($survey) use ($user) {
            $courseMatch = empty($survey->target_course) || $survey->target_course == $user->course;
            $batchMatch = empty($survey->target_batch) || $survey->target_batch == $user->batch;
            
            return $courseMatch && $batchMatch;
        });

        // 3. Map the allowed surveys
        $surveyData = $filteredSurveys->map(function ($survey) use ($user) {
            $hasAnswered = SurveyAnswer::where('survey_id', $survey->id)
                                       ->where('user_id', $user->id)
                                       ->exists();
            return [
                'id' => $survey->id,
                'title' => $survey->title,
                'description' => $survey->description,
                'is_submitted' => $hasAnswered
            ];
        })->values();

        // 🟢 THE FIX: We wrap the data in a 'surveys' object so Flutter can find it!
        return response()->json([
            'surveys' => $surveyData
        ]);
    }

    // Fetches a specific survey, its questions, and previous answers
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $survey = Survey::with('questions')->findOrFail($id);

        $previousAnswers = SurveyAnswer::where('survey_id', $id)
                                   ->where('user_id', $user->id)
                                   ->pluck('answer_text', 'question_id')
                                   ->toArray();

        return response()->json([
            'survey' => $survey,
            'previous_answers' => (object) $previousAnswers, 
            'has_answered' => !empty($previousAnswers)
        ]);
    }

    // Saves the answers coming from the mobile app
    public function store(Request $request, $id)
    {
        $userId = $request->user()->id;

        if (SurveyAnswer::where('survey_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Already submitted'], 400);
        }

        if ($request->has('answers')) {
            foreach ($request->answers as $questionId => $answerData) {
                $answerText = is_array($answerData) ? implode(', ', $answerData) : $answerData;
                if (!empty($answerText)) {
                    SurveyAnswer::create([
                        'survey_id'   => $id,
                        'question_id' => $questionId,
                        'user_id'     => $userId,
                        'answer_text' => $answerText
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}