<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TracerSurvey;
use App\Models\TracerAnswer;

class TracerController extends Controller
{
    // 1. List Surveys
    public function index()
    {
        try {
            $surveys = TracerSurvey::withCount('answers')->latest()->get();
            return view('admin.tracer.index', compact('surveys'));
        } catch (\Exception $e) {
            // This prevents the crash if tables are missing
            return view('admin.tracer.index', ['surveys' => []])
                ->with('error', 'Database Error: Tables are missing. Please run migrations.');
        }
    }

    // 2. Store New Survey
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // 🟢 NEW WAY: Force Save (Bypasses the Mass Assignment Error)
        $survey = new TracerSurvey();
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->status = 1;
        $survey->save();

        return redirect()->back()->with('success', 'Tracer Study created successfully!');
    }

    // 3. View Results (Detailed Analytics View)
    // 🟢 FIXED: Renamed from 'showAnswers' to 'show' to match Route
    public function show($id)
    {
        // 🟢 SAFETY FIX: Removed "with()" to prevent crash on deleted users
        $survey = TracerSurvey::findOrFail($id);
        
        $stats = [
            'total' => $survey->answers->count(),
            'employed' => $survey->answers->where('employment_status', 'Employed')->count(),
            'unemployed' => $survey->answers->where('employment_status', 'Unemployed')->count(),
        ];

        return view('admin.tracer.answers', compact('survey', 'stats'));
    }

    // 4. Export to CSV for CHED Reports
    public function exportAnswers($id)
    {
        // We keep 'with' here for performance, but wrap in try-catch in case of errors
        try {
            $survey = TracerSurvey::with(['answers.user.department'])->findOrFail($id);
        } catch (\Exception $e) {
             $survey = TracerSurvey::findOrFail($id);
        }

        $fileName = 'Tracer_Report_' . str_replace(' ', '_', $survey->title) . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Alumni Name', 'Email', 'Batch', 'Department', 'Status', 'Job Title', 'Company', 'Salary Range', 'Relevance', 'Date Submitted'];

        $callback = function() use($survey, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($survey->answers as $answer) {
                // Use optional() to prevent crash if user is deleted
                $user = $answer->user; 
                
                fputcsv($file, [
                    ($user->first_name ?? 'Unknown') . ' ' . ($user->last_name ?? 'Alumni'),
                    $user->email ?? 'N/A',
                    $user->batch ?? 'N/A',
                    $user->department->name ?? 'N/A',
                    $answer->employment_status,
                    $answer->job_title ?? '--',
                    $answer->company_name ?? '--',
                    $answer->salary_range ?? '--',
                    $answer->is_related ?? '--',
                    $answer->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 5. Delete Survey
    public function destroy($id)
    {
        TracerSurvey::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Survey deleted.');
    }
}