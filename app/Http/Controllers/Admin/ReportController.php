<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MisAlumniRecord;
use Illuminate\Support\Facades\Auth; // Needed for the signatory security check

class ReportController extends Controller
{
    // 🟢 Load Dashboard with Unique Data (No changes here)
    public function index()
    {
        $batches = MisAlumniRecord::whereNotNull('year_graduated')
            ->pluck('year_graduated')
            ->map(function ($year) { return trim($year); })
            ->unique()->sortDesc()->values();

        return view('admin.reports.index', compact('batches'));
    }

    // 🟢 AJAX Course Search (No changes here)
    public function searchCourses(Request $request)
    {
        $search = $request->input('q');
        $query = MisAlumniRecord::whereNotNull('course')->where('course', '!=', '');
        if ($search) { $query->where('course', 'like', "%{$search}%"); }

        $courses = $query->pluck('course')
            ->map(function ($course) { return trim($course); })
            ->unique()->sort()->take(20)->values();
        
        $formatted = [];
        foreach($courses as $c) { $formatted[] = ['id' => $c, 'text' => $c]; }
        return response()->json(['results' => $formatted]);
    }

    // 🟢 FULLY CUSTOMIZABLE PRINT GENERATION
    public function print(Request $request)
    {
        // 1. Get Filter Inputs
        $type = $request->input('report_type');
        $course = $request->input('course');
        $batch_from = $request->input('batch_from');
        $batch_to = $request->input('batch_to');
        
        // 2. Get Custom Column Selection (Default to basic if nothing selected)
        $columns = $request->input('columns', ['fullname', 'course', 'batch']);

        // 3. Base Query (Verified Alumni with MIS data)
        $query = User::with('misRecord')->where('role', 2)->where('status', 1);

        // --- APPLY FILTERS ---

        // Filter: Course
        if (!empty($course) && $course !== 'all') {
            $query->where(function($q) use ($course) {
                $q->where('course', $course)
                  ->orWhereHas('misRecord', function($sub) use ($course) { $sub->where('course', $course); });
            });
        }

        // Filter: Year Range
        if (!empty($batch_from) && $batch_from !== 'all') {
            $query->where(function($q) use ($batch_from) {
                $q->where('batch', '>=', $batch_from)
                  ->orWhereHas('misRecord', function($sub) use ($batch_from) { $sub->where('year_graduated', '>=', $batch_from); });
            });
        }
        if (!empty($batch_to) && $batch_to !== 'all') {
            $query->where(function($q) use ($batch_to) {
                $q->where('batch', '<=', $batch_to)
                  ->orWhereHas('misRecord', function($sub) use ($batch_to) { $sub->where('year_graduated', '<=', $batch_to); });
            });
        }

        // Filter: Employment Status (NEW)
        if ($type === 'employed_only') {
            $query->where('employment_status', 'Employed');
            $docTitle = "Report on Employed Graduates";
        } elseif ($type === 'unemployed_only') {
             // Check for 'Unemployed' OR null/empty status
            $query->where(function($q) {
                $q->where('employment_status', 'Unemployed')
                  ->orWhereNull('employment_status')
                  ->orWhere('employment_status', '');
            });
            $docTitle = "Report on Unemployed / Untracked Graduates";
        } else {
            // Default Title for Masterlist
            $docTitle = "Master List of Graduates";
        }

        // 4. Fetch Data
        $alumni = $query->orderBy('last_name', 'asc')->get();

        // 5. Build Filter Subtitle strings
        $courseText = ($course === 'all' || empty($course)) ? 'All Courses' : $course;
        $batchText = "All Batches";
        if ($batch_from !== 'all' && $batch_to !== 'all') {
             $batchText = ($batch_from == $batch_to) ? "Batch " . $batch_from : "Batches " . $batch_from . "-" . $batch_to;
        } elseif ($batch_from !== 'all') { $batchText = "Batch " . $batch_from . "+";
        } elseif ($batch_to !== 'all') { $batchText = "Up to Batch " . $batch_to; }

        $filterSubtitle = "Filters: " . $courseText . " | " . $batchText;
        
        // Get the currently logged in admin for the signatory security
        $signatory = Auth::user();

        // Return the Portrait View with columns array
        return view('admin.reports.print', compact('alumni', 'columns', 'docTitle', 'filterSubtitle', 'signatory'));
    }
}