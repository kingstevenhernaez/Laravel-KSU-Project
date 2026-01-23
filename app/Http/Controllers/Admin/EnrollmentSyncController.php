<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EnrollmentApi\AlumniSyncService;
use App\Models\KsuEnrollmentSyncLog;
use Illuminate\Http\Request;

class EnrollmentSyncController extends Controller
{
    protected $syncService;

    // Inject the service
    public function __construct(AlumniSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function index()
    {
        $latestLog = KsuEnrollmentSyncLog::latest()->first();
        return view('admin.enrollment_sync.index', compact('latestLog'));
    }

    // MATCHING ROUTE: This must be 'run'
  // MATCHING ROUTE: This must be 'run'
    public function run(Request $request)
    {
        try {
            $count = $this->syncService->syncGraduatedStudents();
            
            // FIX: Return JSON so the button understands the success
            return response()->json([
                'success' => true, 
                'message' => $count . ' Graduates successfully synced.'
            ]);
        } catch (\Exception $e) {
            // FIX: Return JSON error so the button shows the real reason
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}