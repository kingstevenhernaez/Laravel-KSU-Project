<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PassingYear;
use App\Models\TracerSurvey;
use App\Models\TracerSurveyAnswer;
use App\Models\TracerSurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TracerSurveyReportController extends Controller
{
    /**
     * Admin: Overall tracer analytics dashboard.
     */
    public function analytics(Request $request)
    {
        $surveys = TracerSurvey::orderByDesc('id')->get();
        $departments = Department::orderBy('name')->get();
        $years = PassingYear::orderBy('name', 'desc')->get();

        // Optional filter: department_id / passing_year_id to compute response rates
        $responseBase = TracerSurveyResponse::query()
            ->when($request->filled('department_id'), function ($q) use ($request) {
                $q->whereHas('alumni', fn($a) => $a->where('department_id', $request->department_id));
            })
            ->when($request->filled('passing_year_id'), function ($q) use ($request) {
                $q->whereHas('alumni', fn($a) => $a->where('passing_year_id', $request->passing_year_id));
            });

        $totalResponses = (clone $responseBase)->count();

        // Responses per survey
        $responsesPerSurvey = (clone $responseBase)
            ->select('survey_id', DB::raw('COUNT(*) as total'))
            ->groupBy('survey_id')
            ->orderByDesc('total')
            ->get()
            ->keyBy('survey_id');

        // Responses by department (all surveys)
        $responsesByDepartment = (clone $responseBase)
            ->join('alumnus', 'tracer_survey_responses.alumni_id', '=', 'alumnus.id')
            ->join('departments', 'alumnus.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('COUNT(tracer_survey_responses.id) as total'))
            ->groupBy('departments.name')
            ->orderByDesc('total')
            ->get();

        // Responses by passing year (all surveys)
        $responsesByYear = (clone $responseBase)
            ->join('alumnus', 'tracer_survey_responses.alumni_id', '=', 'alumnus.id')
            ->join('passing_years', 'alumnus.passing_year_id', '=', 'passing_years.id')
            ->select('passing_years.name as year', DB::raw('COUNT(tracer_survey_responses.id) as total'))
            ->groupBy('passing_years.name')
            ->orderByDesc('year')
            ->get();

        return view('admin.reports.tracer_surveys.analytics', compact(
            'surveys',
            'departments',
            'years',
            'totalResponses',
            'responsesPerSurvey',
            'responsesByDepartment',
            'responsesByYear'
        ));
    }

    /**
     * Admin: Per-survey results (question breakdown + counts).
     */
    public function results(TracerSurvey $survey, Request $request)
    {
        $survey->load(['questions.options']);

        $responses = TracerSurveyResponse::query()
            ->where('survey_id', $survey->id)
            ->with(['alumni.user', 'alumni.department', 'alumni.passing_year'])
            ->orderByDesc('submitted_at');

        $responseCount = (clone $responses)->count();
        $latest = (clone $responses)->first();

        // Option counts per question
        $optionCounts = TracerSurveyAnswer::query()
            ->join('tracer_survey_responses', 'tracer_survey_answers.response_id', '=', 'tracer_survey_responses.id')
            ->where('tracer_survey_responses.survey_id', $survey->id)
            ->select('tracer_survey_answers.question_id', 'tracer_survey_answers.option_id', DB::raw('COUNT(*) as total'))
            ->groupBy('tracer_survey_answers.question_id', 'tracer_survey_answers.option_id')
            ->get();

        $optionCountsMap = [];
        foreach ($optionCounts as $row) {
            $optionCountsMap[$row->question_id][$row->option_id] = (int)$row->total;
        }

        // Free-text answers per question (limited)
        $textAnswers = TracerSurveyAnswer::query()
            ->join('tracer_survey_responses', 'tracer_survey_answers.response_id', '=', 'tracer_survey_responses.id')
            ->where('tracer_survey_responses.survey_id', $survey->id)
            ->whereNotNull('tracer_survey_answers.answer_text')
            ->where('tracer_survey_answers.answer_text', '!=', '')
            ->select('tracer_survey_answers.question_id', 'tracer_survey_answers.answer_text')
            ->orderByDesc('tracer_survey_answers.id')
            ->limit(200)
            ->get()
            ->groupBy('question_id');

        $responsesPage = $responses->paginate(20)->appends($request->query());

        return view('admin.reports.tracer_surveys.results', compact(
            'survey',
            'responseCount',
            'latest',
            'optionCountsMap',
            'textAnswers',
            'responsesPage'
        ));
    }

    /**
     * Admin: Export survey responses to CSV (one row per alumni response; answers flattened).
     */
    public function export(TracerSurvey $survey): StreamedResponse
    {
        $survey->load(['questions.options']);
        $questions = $survey->questions;
        $filename = 'tracer_survey_' . $survey->id . '_export_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($survey, $questions) {
            $out = fopen('php://output', 'w');

            $header = [
                'Submitted At',
                'Alumni Name',
                'Student/ID',
                'Department',
                'Passing Year',
            ];
            foreach ($questions as $q) {
                $header[] = 'Q' . $q->id . ': ' . $q->question;
            }
            fputcsv($out, $header);

            TracerSurveyResponse::query()
                ->where('survey_id', $survey->id)
                ->with(['alumni.user', 'alumni.department', 'alumni.passing_year', 'answers'])
                ->orderByDesc('submitted_at')
                ->chunk(200, function ($rows) use ($out, $questions) {
                    foreach ($rows as $r) {
                        $al = $r->alumni;
                        $ansMap = [];
                        foreach ($r->answers as $a) {
                            $ansMap[$a->question_id][] = $a->answer_text ?: (string) $a->option_id;
                        }

                        $line = [
                            optional($r->submitted_at)->format('Y-m-d H:i:s'),
                            optional($al?->user)->name,
                            $al?->id_number,
                            optional($al?->department)->name,
                            optional($al?->passing_year)->name,
                        ];

                        foreach ($questions as $q) {
                            $line[] = isset($ansMap[$q->id]) ? implode(' | ', $ansMap[$q->id]) : '';
                        }

                        fputcsv($out, $line);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
