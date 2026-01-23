<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KsuEnrollmentSyncLog;
use App\Services\EnrollmentSyncService;
use Illuminate\Http\Request;

class EnrollmentSyncController extends Controller
{
    public function __construct(private readonly EnrollmentSyncService $sync)
    {
    }

    public function index()
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;

        $q = KsuEnrollmentSyncLog::query();
        if (!is_null($tenantId)) {
            $q->where('tenant_id', $tenantId);
        }

        $latestLog = $q->latest('id')->first();

        return view('admin.enrollment_sync.index', compact('latestLog'));
    }

    public function run(Request $request)
    {
        $log = $this->sync->run();

        $payload = [
            'status' => 'success',
            'message' => 'Sync complete (inserted: ' . $log->inserted . ', updated: ' . $log->updated . ', failed: ' . $log->failed . ').',
            'log' => [
                'id' => $log->id,
                'started_at' => optional($log->started_at)->toDateTimeString(),
                'finished_at' => optional($log->finished_at)->toDateTimeString(),
                'inserted' => $log->inserted,
                'updated' => $log->updated,
                'failed' => $log->failed,
                'error_summary' => $log->error_summary,
            ],
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($payload);
        }

        return redirect()
            ->route('admin.enrollment_sync.index')
            ->with('success', $payload['message']);
    }
}
