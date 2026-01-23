<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\PassingYear;
use App\Models\TracerSurvey;
use App\Models\TracerSurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TracerSurveyController extends Controller
{
    public function index(): View
    {
        $tenantId = getTenantId();
        $surveys = TracerSurvey::where('tenant_id', $tenantId)->orderByDesc('id')->paginate(20);

        return view('admin.tracer_surveys.index', compact('surveys'));
    }

    public function create(): View
    {
        $tenantId = getTenantId();
        return view('admin.tracer_surveys.create', [
            'departments' => Department::where('tenant_id', $tenantId)->orderBy('name')->get(),
            'passingYears' => PassingYear::where('tenant_id', $tenantId)->orderBy('name', 'desc')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $v = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_passing_year_ids' => ['nullable', 'array'],
            'target_passing_year_ids.*' => ['integer'],
            'target_department_ids' => ['nullable', 'array'],
            'target_department_ids.*' => ['integer'],
            'target_colleges' => ['nullable', 'string'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $tenantId = getTenantId();
        $survey = new TracerSurvey();
        $survey->tenant_id = $tenantId;
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->is_published = false;
        $survey->target_rules = [
            'passing_year_ids' => array_values(array_filter((array) $request->get('target_passing_year_ids', []))),
            'department_ids' => array_values(array_filter((array) $request->get('target_department_ids', []))),
            'colleges' => $this->parseColleges((string) $request->get('target_colleges', '')),
        ];
        $survey->save();

        return redirect()->route('admin.tracer_surveys.edit', $survey->id)->with('success', __('Survey created.'));
    }

    public function edit($id): View
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($id);
        $survey->load(['questions.options']);

        return view('admin.tracer_surveys.edit', [
            'survey' => $survey,
            'departments' => Department::where('tenant_id', $tenantId)->orderBy('name')->get(),
            'passingYears' => PassingYear::where('tenant_id', $tenantId)->orderBy('name', 'desc')->get(),
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $v = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_passing_year_ids' => ['nullable', 'array'],
            'target_passing_year_ids.*' => ['integer'],
            'target_department_ids' => ['nullable', 'array'],
            'target_department_ids.*' => ['integer'],
            'target_colleges' => ['nullable', 'string'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($id);
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->target_rules = [
            'passing_year_ids' => array_values(array_filter((array) $request->get('target_passing_year_ids', []))),
            'department_ids' => array_values(array_filter((array) $request->get('target_department_ids', []))),
            'colleges' => $this->parseColleges((string) $request->get('target_colleges', '')),
        ];
        $survey->save();

        return back()->with('success', __('Survey updated.'));
    }

    public function publish($id): RedirectResponse
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($id);
        $survey->is_published = true;
        $survey->save();

        return back()->with('success', __('Survey published.'));
    }

    public function unpublish($id): RedirectResponse
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($id);
        $survey->is_published = false;
        $survey->save();

        return back()->with('success', __('Survey unpublished.'));
    }

    public function delete($id): RedirectResponse
    {
        $tenantId = getTenantId();
        $survey = TracerSurvey::where('tenant_id', $tenantId)->findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.tracer_surveys.index')->with('success', __('Survey deleted.'));
    }

    private function parseColleges(string $raw): array
    {
        $parts = array_map('trim', preg_split('/[\n,]+/', $raw) ?: []);
        return array_values(array_filter(array_unique($parts)));
    }
}
