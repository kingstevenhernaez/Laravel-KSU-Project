<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\TracerSurvey;
use App\Models\TracerSurveyAnswer;
use App\Models\TracerSurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TracerSurveyController extends Controller
{
    private function alumnusOrFail(): Alumni
    {
        $tenantId = getTenantId();
        $userId = auth()->id();
        $alumnus = Alumni::where('tenant_id', $tenantId)->where('user_id', $userId)->first();
        if (!$alumnus) {
            abort(403, __('Alumni profile not found.'));
        }
        return $alumnus;
    }

    public function index(): View
    {
        $tenantId = getTenantId();
        $alumnus = $this->alumnusOrFail();

        $surveys = TracerSurvey::where('tenant_id', $tenantId)
            ->where('is_published', true)
            ->get()
            ->filter(function (TracerSurvey $s) use ($alumnus) {
                return $this->matchesTargets($s, $alumnus);
            })
            ->values();

        $submittedIds = TracerSurveyResponse::where('tenant_id', $tenantId)
            ->where('alumni_id', $alumnus->id)
            ->pluck('survey_id')
            ->all();

        return view('alumni.tracer_surveys.index', [
            'alumnus' => $alumnus,
            'surveys' => $surveys,
            'submittedIds' => $submittedIds,
        ]);
    }

    public function show($id): View
    {
        $tenantId = getTenantId();
        $alumnus = $this->alumnusOrFail();

        $survey = TracerSurvey::where('tenant_id', $tenantId)
            ->where('is_published', true)
            ->with(['questions.options'])
            ->findOrFail($id);

        if (!$this->matchesTargets($survey, $alumnus)) {
            abort(403, __('This survey is not assigned to you.'));
        }

        $existing = TracerSurveyResponse::where('tenant_id', $tenantId)
            ->where('survey_id', $survey->id)
            ->where('alumni_id', $alumnus->id)
            ->first();

        return view('alumni.tracer_surveys.show', [
            'alumnus' => $alumnus,
            'survey' => $survey,
            'existing' => $existing,
        ]);
    }

    public function submit(Request $request, $id): RedirectResponse
    {
        $tenantId = getTenantId();
        $alumnus = $this->alumnusOrFail();

        $survey = TracerSurvey::where('tenant_id', $tenantId)
            ->where('is_published', true)
            ->with(['questions.options'])
            ->findOrFail($id);

        if (!$this->matchesTargets($survey, $alumnus)) {
            abort(403, __('This survey is not assigned to you.'));
        }

        $existing = TracerSurveyResponse::where('tenant_id', $tenantId)
            ->where('survey_id', $survey->id)
            ->where('alumni_id', $alumnus->id)
            ->first();
        if ($existing) {
            return redirect()->route('tracer_surveys.show', $survey->id)->with('success', __('You have already submitted this survey.'));
        }

        // validate required questions
        $rules = [];
        foreach ($survey->questions as $q) {
            $key = 'q_' . $q->id;
            if ($q->is_required) {
                $rules[$key] = ['required'];
            } else {
                $rules[$key] = ['nullable'];
            }
        }
        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        DB::beginTransaction();
        try {
            $resp = new TracerSurveyResponse();
            $resp->tenant_id = $tenantId;
            $resp->survey_id = $survey->id;
            $resp->alumni_id = $alumnus->id;
            $resp->submitted_at = now();
            $resp->save();

            foreach ($survey->questions as $q) {
                $key = 'q_' . $q->id;
                $answerVal = $request->input($key);
                if (is_array($answerVal)) {
                    $answerVal = json_encode($answerVal);
                }

                $ans = new TracerSurveyAnswer();
                $ans->response_id = $resp->id;
                $ans->question_id = $q->id;
                $ans->answer = $answerVal;
                $ans->save();
            }

            DB::commit();
            return redirect()->route('tracer_surveys.index')->with('success', __('Survey submitted.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function matchesTargets(TracerSurvey $survey, Alumni $alumnus): bool
    {
        $rules = is_array($survey->target_rules) ? $survey->target_rules : (json_decode((string) $survey->target_rules, true) ?: []);
        $yearIds = array_values(array_filter((array) ($rules['passing_year_ids'] ?? [])));
        $deptIds = array_values(array_filter((array) ($rules['department_ids'] ?? [])));
        $colleges = array_values(array_filter((array) ($rules['colleges'] ?? [])));

        if (!empty($yearIds) && !in_array((int) $alumnus->passing_year_id, array_map('intval', $yearIds), true)) {
            return false;
        }
        if (!empty($deptIds) && !in_array((int) $alumnus->department_id, array_map('intval', $deptIds), true)) {
            return false;
        }
        // College matching: optional; if rules specify colleges, try match from alumnus custom_fields.
        if (!empty($colleges)) {
            $hay = strtolower((string) ($alumnus->custom_fields ?? ''));
            $ok = false;
            foreach ($colleges as $c) {
                if ($c !== '' && str_contains($hay, strtolower($c))) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                return false;
            }
        }
        return true;
    }
}
