<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\KsuAlumniRecord;
use Illuminate\Http\Request;

class AlumniReportController extends Controller
{
    public function index()
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;

        $base = KsuAlumniRecord::query();
        if (!is_null($tenantId)) {
            $base->where('tenant_id', $tenantId);
        }

        $years = (clone $base)
            ->whereNotNull('graduation_year')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year')
            ->values();

        $programs = (clone $base)
            ->whereNotNull('program_name')
            ->distinct()
            ->orderBy('program_name')
            ->pluck('program_name')
            ->values();

        return view('admin.analytics.alumni_report_builder', compact('years', 'programs'));
    }

    public function print(Request $request)
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;

        $validated = $request->validate([
            'graduation_year' => ['nullable'],
            'program_name' => ['nullable', 'string'],
            'columns' => ['required', 'array', 'min:1'],
            'columns.*' => ['string'],
        ]);

        $q = KsuAlumniRecord::query();
        if (!is_null($tenantId)) {
            $q->where('tenant_id', $tenantId);
        }

        if (!empty($validated['graduation_year'])) {
            $q->where('graduation_year', (int) $validated['graduation_year']);
        }
        if (!empty($validated['program_name'])) {
            $q->where('program_name', $validated['program_name']);
        }

        $allowedCols = [
            'student_number' => 'Student No.',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'program_code' => 'Program Code',
            'program_name' => 'Program',
            'graduation_year' => 'Grad Year',
        ];

        $columns = array_values(array_intersect($validated['columns'], array_keys($allowedCols)));
        if (!$columns) {
            $columns = ['student_number', 'first_name', 'last_name', 'program_name', 'graduation_year'];
        }

        $rows = $q->orderBy('last_name')->limit(5000)->get($columns);

        $filters = [
            'graduation_year' => $validated['graduation_year'] ?? null,
            'program_name' => $validated['program_name'] ?? null,
        ];

        return view('admin.analytics.alumni_report_print', [
            'rows' => $rows,
            'columns' => $columns,
            'labels' => $allowedCols,
            'filters' => $filters,
        ]);
    }
}
