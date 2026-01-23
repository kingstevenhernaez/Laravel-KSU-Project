<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Department;
use App\Models\PassingYear;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $tenantId = getTenantId();

        $search = trim((string) $request->get('q', ''));
        $departmentId = $request->get('department_id');
        $passingYearId = $request->get('passing_year_id');
        $college = trim((string) $request->get('college', ''));

        $query = Alumni::with(['user', 'department', 'passing_year'])
            ->where('tenant_id', $tenantId)
            ->whereNotNull('user_id');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        if ($passingYearId) {
            $query->where('passing_year_id', $passingYearId);
        }

        // College is stored in synced record, not necessarily in alumni table.
        // We keep a soft filter using alumnus.custom_fields if present.
        if ($college !== '') {
            $query->where(function ($q) use ($college) {
                $q->where('custom_fields', 'LIKE', '%' . $college . '%')
                  ->orWhere('address', 'LIKE', '%' . $college . '%');
            });
        }

        if ($search !== '') {
            $query->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $alumni = $query->orderBy('id', 'desc')->paginate(20)->appends($request->query());

        return view('frontend.directory.index', [
            'alumni' => $alumni,
            'search' => $search,
            'departments' => Department::where('tenant_id', $tenantId)->orderBy('name')->get(),
            'passingYears' => PassingYear::where('tenant_id', $tenantId)->orderBy('name', 'desc')->get(),
            'selectedDepartment' => $departmentId,
            'selectedPassingYear' => $passingYearId,
            'selectedCollege' => $college,
        ]);
    }
}
