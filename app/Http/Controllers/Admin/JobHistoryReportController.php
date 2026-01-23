<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\JobHistory;
use App\Models\PassingYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobHistoryReportController extends Controller
{
    /**
     * Admin: Job History master list with filters.
     */
    public function index(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $years = PassingYear::orderBy('name', 'desc')->get();

        $q = JobHistory::query()
            ->with(['alumni.user', 'alumni.department', 'alumni.passing_year'])
            ->when($request->filled('department_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('department_id', $request->department_id));
            })
            ->when($request->filled('passing_year_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('passing_year_id', $request->passing_year_id));
            })
            ->when($request->filled('industry'), fn($qq) => $qq->where('industry', $request->industry))
            ->when($request->filled('employment_type'), fn($qq) => $qq->where('employment_type', $request->employment_type))
            ->when($request->filled('is_current'), function ($qq) use ($request) {
                $qq->where('is_current', (int)$request->is_current === 1);
            })
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->orderByDesc('id');

        $items = $q->paginate(20)->appends($request->query());

        $industryOptions = JobHistory::query()->select('industry')->whereNotNull('industry')->distinct()->orderBy('industry')->pluck('industry');
        $employmentTypeOptions = JobHistory::query()->select('employment_type')->whereNotNull('employment_type')->distinct()->orderBy('employment_type')->pluck('employment_type');

        return view('admin.reports.job_history.index', compact('items', 'departments', 'years', 'industryOptions', 'employmentTypeOptions'));
    }

    /**
     * Admin: Job history analytics dashboard.
     */
    public function analytics(Request $request)
    {
        // Base filter: scope to alumni department/year if provided
        $base = JobHistory::query()
            ->when($request->filled('department_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('department_id', $request->department_id));
            })
            ->when($request->filled('passing_year_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('passing_year_id', $request->passing_year_id));
            });

        $totalRecords = (clone $base)->count();
        $currentJobs = (clone $base)->where('is_current', true)->count();

        // Top industries
        $topIndustries = (clone $base)
            ->select('industry', DB::raw('COUNT(*) as total'))
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->groupBy('industry')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top employers
        $topEmployers = (clone $base)
            ->select('company_name', DB::raw('COUNT(*) as total'))
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->groupBy('company_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Employment type breakdown
        $employmentTypes = (clone $base)
            ->select('employment_type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('employment_type')
            ->where('employment_type', '!=', '')
            ->groupBy('employment_type')
            ->orderByDesc('total')
            ->get();

        // Jobs by department
        $jobsByDepartment = (clone $base)
            ->join('alumnus', 'job_histories.alumni_id', '=', 'alumnus.id')
            ->join('departments', 'alumnus.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('COUNT(job_histories.id) as total'))
            ->groupBy('departments.name')
            ->orderByDesc('total')
            ->get();

        // Jobs by passing year
        $jobsByYear = (clone $base)
            ->join('alumnus', 'job_histories.alumni_id', '=', 'alumnus.id')
            ->join('passing_years', 'alumnus.passing_year_id', '=', 'passing_years.id')
            ->select('passing_years.name as year', DB::raw('COUNT(job_histories.id) as total'))
            ->groupBy('passing_years.name')
            ->orderByDesc('year')
            ->get();

        $departments = Department::orderBy('name')->get();
        $years = PassingYear::orderBy('name', 'desc')->get();

        return view('admin.reports.job_history.analytics', compact(
            'totalRecords',
            'currentJobs',
            'topIndustries',
            'topEmployers',
            'employmentTypes',
            'jobsByDepartment',
            'jobsByYear',
            'departments',
            'years'
        ));
    }

    /**
     * Admin: Export CSV of job histories with filters.
     */
    public function export(Request $request): StreamedResponse
    {
        $q = JobHistory::query()
            ->with(['alumni.user', 'alumni.department', 'alumni.passing_year'])
            ->when($request->filled('department_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('department_id', $request->department_id));
            })
            ->when($request->filled('passing_year_id'), function ($qq) use ($request) {
                $qq->whereHas('alumni', fn($a) => $a->where('passing_year_id', $request->passing_year_id));
            })
            ->when($request->filled('industry'), fn($qq) => $qq->where('industry', $request->industry))
            ->when($request->filled('employment_type'), fn($qq) => $qq->where('employment_type', $request->employment_type))
            ->when($request->filled('is_current'), function ($qq) use ($request) {
                $qq->where('is_current', (int)$request->is_current === 1);
            })
            ->orderByDesc('id');

        $filename = 'job_history_export_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Alumni Name',
                'Student/ID',
                'Department',
                'Passing Year',
                'Company',
                'Job Title',
                'Industry',
                'Employment Type',
                'Location',
                'Start Date',
                'End Date',
                'Current',
            ]);

            $q->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    $al = $r->alumni;
                    fputcsv($out, [
                        optional($al?->user)->name,
                        $al?->id_number,
                        optional($al?->department)->name,
                        optional($al?->passing_year)->name,
                        $r->company_name,
                        $r->job_title,
                        $r->industry,
                        $r->employment_type,
                        $r->location,
                        optional($r->start_date)->format('Y-m-d'),
                        optional($r->end_date)->format('Y-m-d'),
                        $r->is_current ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
