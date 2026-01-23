<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerSurvey;
use App\Models\TracerSurveyOption;
use App\Models\TracerSurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TracerSurveyQuestionController extends Controller
{
    public function store(Request $request, $surveyId): RedirectResponse
    {
        $v = Validator::make($request->all(), [
            'question_text' => ['required', 'string', 'max:1000'],
            'question_type' => ['required', 'string', 'max:50'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($surveyId);

        $q = new TracerSurveyQuestion();
        $q->survey_id = $survey->id;
        $q->question_text = $request->question_text;
        $q->question_type = $request->question_type;
        $q->is_required = (bool) $request->boolean('is_required');
        $q->sort_order = (int) ($request->sort_order ?? 0);
        $q->save();

        return back()->with('success', __('Question added.'));
    }

    public function delete(Request $request, $surveyId, $questionId): RedirectResponse
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($surveyId);
        $q = TracerSurveyQuestion::where('survey_id', $survey->id)->findOrFail($questionId);
        $q->delete();

        return back()->with('success', __('Question deleted.'));
    }

    public function storeOption(Request $request, $surveyId, $questionId): RedirectResponse
    {
        $v = Validator::make($request->all(), [
            'option_text' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($surveyId);
        $q = TracerSurveyQuestion::where('survey_id', $survey->id)->findOrFail($questionId);

        $opt = new TracerSurveyOption();
        $opt->question_id = $q->id;
        $opt->option_text = $request->option_text;
        $opt->sort_order = (int) ($request->sort_order ?? 0);
        $opt->save();

        return back()->with('success', __('Option added.'));
    }

    public function deleteOption(Request $request, $surveyId, $questionId, $optionId): RedirectResponse
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($surveyId);
        $q = TracerSurveyQuestion::where('survey_id', $survey->id)->findOrFail($questionId);
        $opt = TracerSurveyOption::where('question_id', $q->id)->findOrFail($optionId);
        $opt->delete();

        return back()->with('success', __('Option deleted.'));
    }
}
