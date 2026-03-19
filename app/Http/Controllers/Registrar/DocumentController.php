<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentRequest;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role != 3) {
                abort(403, 'Unauthorized Access. Registrar only.');
            }
            return $next($request);
        });
    }

    // 🟢 UPDATED: Dashboard now accepts a "status" filter
    public function index(Request $request)
    {
        $status = $request->get('status', 'All');

        $query = DocumentRequest::with('user')->orderBy('created_at', 'desc');

        // Apply filter if a specific tab is clicked
        if ($status !== 'All') {
            $query->where('status', $status);
        }

        // Pagination ensures it never slows down even with 10,000 requests
        $requests = $query->paginate(15);
        
        $pendingCount = DocumentRequest::where('status', 'Pending')->count();
        $processingCount = DocumentRequest::where('status', 'Processing')->count();
        $readyCount = DocumentRequest::where('status', 'Ready for Pickup')->count();

        return view('registrar.dashboard', compact('requests', 'pendingCount', 'processingCount', 'readyCount', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $docRequest = DocumentRequest::findOrFail($id);
        $docRequest->status = $request->status;
        
        if ($request->filled('remarks')) {
            $docRequest->remarks = $request->remarks;
        }

        $docRequest->save();

        return back()->with('success', 'Document status updated to ' . $request->status . '!');
    }

 // 🟢 UPDATED: Advanced Analytics and Filtering
    public function reports(Request $request)
    {
        // 1. Fetch dropdown data for the filters
        $courses = \App\Models\User::whereNotNull('course')->where('course', '!=', '')->distinct()->orderBy('course')->pluck('course');
        $batches = \App\Models\User::whereNotNull('batch')->where('batch', '!=', '')->distinct()->orderBy('batch', 'desc')->pluck('batch');
        $docTypes = DocumentRequest::distinct()->pluck('document_type');

        // 2. Build the Advanced Filtered Query
        $query = DocumentRequest::with('user')->orderBy('created_at', 'desc');

        // Filter by Course & Batch (from the users table)
        if ($request->filled('course') || $request->filled('batch')) {
            $query->whereHas('user', function($q) use ($request) {
                if ($request->filled('course')) {
                    $q->where('course', $request->course);
                }
                if ($request->filled('batch')) {
                    $q->where('batch', $request->batch);
                }
            });
        }

        // Filter by Document Type & Status
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Get the paginated results for the targeted table
        $filteredRequests = $query->paginate(25)->appends($request->all());

        // 4. Get general statistics (ignoring filters for the top widgets)
        $byDocType = DocumentRequest::select('document_type', DB::raw('count(*) as total'))->groupBy('document_type')->orderBy('total', 'desc')->get();
        $byCourse = DocumentRequest::join('users', 'document_requests.user_id', '=', 'users.id')->select('users.course', DB::raw('count(document_requests.id) as total'))->groupBy('users.course')->orderBy('total', 'desc')->get();

        return view('registrar.reports', compact('filteredRequests', 'courses', 'batches', 'docTypes', 'byDocType', 'byCourse'));
    }

    // 🟢 NEW: Generates the clean, printable version of the targeted report
    public function printReport(Request $request)
    {
        $query = DocumentRequest::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('course') || $request->filled('batch')) {
            $query->whereHas('user', function($q) use ($request) {
                if ($request->filled('course')) $q->where('course', $request->course);
                if ($request->filled('batch')) $q->where('batch', $request->batch);
            });
        }
        if ($request->filled('document_type')) $query->where('document_type', $request->document_type);
        if ($request->filled('status')) $query->where('status', $request->status);

        // For printing, we get EVERYTHING that matches the filter (no pagination)
        $requests = $query->get();

        return view('registrar.print_report', compact('requests', 'request'));
    }
}