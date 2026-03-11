<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Survey;
use App\Models\SurveyQuestion;

class TracerController extends Controller
{
    // 1. List Surveys
    public function index()
    {
        $surveys = Survey::withCount('answers')->latest()->get();
        return view('admin.tracer.index', compact('surveys'));
    }

    // 2. Show the Survey Builder Form
    public function create()
    {
        // Fetch unique courses and batches for the targeting dropdowns
        $courses = \App\Models\User::where('role', 2)->whereNotNull('course')->where('course', '!=', '')->distinct()->orderBy('course')->pluck('course');
        $batches = \App\Models\User::where('role', 2)->whereNotNull('batch')->where('batch', '!=', '')->distinct()->orderBy('batch', 'desc')->pluck('batch');
        
        return view('admin.tracer.create', compact('courses', 'batches'));
    }

    // 3. Store Dynamic Survey and Questions
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1', 
        ]);

        // Step A: Save the Main Survey
        $survey = new Survey();
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->target_course = $request->target_course;
        $survey->target_batch = $request->target_batch;
        $survey->is_active = 1;
        $survey->is_ched_template = $request->has('is_ched_template') ? 1 : 0;
        $survey->created_by = Auth::id(); 
        $survey->save();

        // Step B: Loop through questions
        foreach ($request->questions as $index => $q) {
            $question = new SurveyQuestion();
            $question->survey_id = $survey->id;
            $question->question_text = $q['text'];
            $question->answer_type = $q['type']; 
            
            if (in_array($q['type'], ['dropdown', 'radio', 'checkbox']) && !empty($q['options'])) {
                $optionsArray = array_map('trim', explode(',', $q['options']));
                $question->options = $optionsArray; 
            }
            
            $question->order_num = $index;
            $question->is_required = isset($q['is_required']) ? 1 : 0;
            $question->save();
        }

        return redirect()->route('admin.tracer.index')->with('success', 'Tracer Study and questions published successfully!');
    }

    // 4. View Dynamic Results
    public function show($id)
    {
        $survey = Survey::with(['questions', 'answers.user'])->findOrFail($id);
        
        // Group the raw answers by the user_id
        $responsesByUser = $survey->answers->groupBy('user_id');

        return view('admin.tracer.answers', compact('survey', 'responsesByUser'));
    }

    // 5. Export Dynamic Survey to CSV
    public function exportAnswers($id)
    {
        $survey = Survey::with(['questions', 'answers.user'])->findOrFail($id);
        $responsesByUser = $survey->answers->groupBy('user_id');

        $fileName = 'Tracer_Report_' . \Illuminate\Support\Str::slug($survey->title) . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Build the CSV Header Row dynamically
        $columns = ['Alumni Name', 'Email', 'Batch'];
        foreach ($survey->questions as $q) {
            $columns[] = $q->question_text;
        }
        $columns[] = 'Date Submitted';

        $callback = function() use($survey, $responsesByUser, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for proper Excel UTF-8 reading
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF))); 
            
            fputcsv($file, $columns);

            foreach ($responsesByUser as $userId => $userAnswers) {
                $user = $userAnswers->first()->user;
                if (!$user) continue; 
                
                $row = [
                    $user->first_name . ' ' . $user->last_name,
                    $user->email ?? 'N/A',
                    $user->batch ?? 'N/A',
                ];
                
                // Map the answers to the correct question column
                $mappedAnswers = $userAnswers->pluck('answer_text', 'question_id');
                
                foreach ($survey->questions as $q) {
                    $row[] = $mappedAnswers[$q->id] ?? '--';
                }
                
                $row[] = $userAnswers->first()->created_at->format('Y-m-d');
                
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 6. Delete Survey
    public function destroy($id)
    {
        Survey::findOrFail($id)->delete(); 
        return redirect()->back()->with('success', 'Survey deleted.');
    }
}