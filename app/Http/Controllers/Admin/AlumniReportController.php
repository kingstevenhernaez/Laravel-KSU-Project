<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::query();

        foreach (['graduation_year','college','program','status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->$field);
            }
        }

        $alumni = $query->get();

        return view('admin.analytics.alumni_report', compact('alumni'));
    }
}
