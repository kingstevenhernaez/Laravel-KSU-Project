<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\JobHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class JobHistoryController extends Controller
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
        $alumnus = $this->alumnusOrFail();
        $jobs = JobHistory::where('alumni_id', $alumnus->id)->orderByDesc('is_current')->orderByDesc('start_date')->get();

        return view('alumni.job_history.index', compact('alumnus', 'jobs'));
    }

    public function create(): View
    {
        $alumnus = $this->alumnusOrFail();
        return view('alumni.job_history.form', [
            'alumnus' => $alumnus,
            'job' => new JobHistory(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $alumnus = $this->alumnusOrFail();

        $v = Validator::make($request->all(), [
            'company' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        DB::beginTransaction();
        try {
            $job = new JobHistory();
            $job->alumni_id = $alumnus->id;
            $job->company_name = $request->company; 
            $job->job_title    = $request->position; 
            $job->location = $request->location;
            $job->start_date = $request->start_date;
            $job->end_date = $request->end_date;
            $job->is_current = (bool) $request->boolean('is_current');
            $job->description = $request->description;
            $job->save();

            if ($job->is_current) {
                JobHistory::where('alumni_id', $alumnus->id)
                    ->where('id', '!=', $job->id)
                    ->update(['is_current' => 0]);
            }

            DB::commit();
            return redirect()->route('job_history.index')->with('success', __('Job added.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        $alumnus = $this->alumnusOrFail();
        $job = JobHistory::where('alumni_id', $alumnus->id)->findOrFail($id);

        return view('alumni.job_history.form', [
            'alumnus' => $alumnus,
            'job' => $job,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $alumnus = $this->alumnusOrFail();
        $job = JobHistory::where('alumni_id', $alumnus->id)->findOrFail($id);

        $v = Validator::make($request->all(), [
            'company' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        DB::beginTransaction();
        try {
            $job->company_name = $request->company;
            $job->job_title    = $request->position;
            $job->location = $request->location;
            $job->start_date = $request->start_date;
            $job->end_date = $request->end_date;
            $job->is_current = (bool) $request->boolean('is_current');
            $job->description = $request->description;
            $job->save();

            if ($job->is_current) {
                JobHistory::where('alumni_id', $alumnus->id)
                    ->where('id', '!=', $job->id)
                    ->update(['is_current' => 0]);
            }

            DB::commit();
            return redirect()->route('job_history.index')->with('success', __('Job updated.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id): RedirectResponse
    {
        $alumnus = $this->alumnusOrFail();
        $job = JobHistory::where('alumni_id', $alumnus->id)->findOrFail($id);
        $job->delete();

        return redirect()->route('job_history.index')->with('success', __('Job deleted.'));
    }

    public function setCurrent($id): RedirectResponse
    {
        $alumnus = $this->alumnusOrFail();
        $job = JobHistory::where('alumni_id', $alumnus->id)->findOrFail($id);

        DB::transaction(function () use ($alumnus, $job) {
            JobHistory::where('alumni_id', $alumnus->id)->update(['is_current' => 0]);
            $job->is_current = 1;
            $job->save();
        });

        return redirect()->route('job_history.index')->with('success', __('Current job updated.'));
    }
}
