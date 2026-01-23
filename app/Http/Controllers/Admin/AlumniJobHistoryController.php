<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\JobHistory;
use Illuminate\View\View;

class AlumniJobHistoryController extends Controller
{
    public function index($alumniId): View
    {
        $tenantId = getTenantId();
        $alumnus = Alumni::with(['user', 'department', 'passing_year'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($alumniId);

        $jobs = JobHistory::where('alumni_id', $alumnus->id)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return view('admin.manage_alumni.job_history.index', compact('alumnus', 'jobs'));
    }
}
