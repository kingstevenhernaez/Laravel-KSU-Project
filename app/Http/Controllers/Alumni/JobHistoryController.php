<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\JobHistory;
use App\Services\EnrollmentApi\AlumniSyncService; // FIX: Import Service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobHistoryController extends Controller
{
    /**
     * Store new job and push to Mock System.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company'    => 'required|string',
            'position'   => 'required|string',
            'start_date' => 'required|date',
        ]);

        $alumni = auth()->user()->alumni;

        DB::beginTransaction();
        try {
            // 1. Save locally to Alumni System
            $job = new JobHistory();
            $job->alumni_id = $alumni->id;
            $job->company = $request->company;
            $job->position = $request->position;
            $job->start_date = $request->start_date;
            $job->save();

            // 2. TRIGGER PUSH: Save to Mock Enrollment System
            $syncService = app(AlumniSyncService::class);
            $syncService->updateJobInEnrollment($alumni->id_number, $request->only(['company', 'position', 'start_date']));

            DB::commit();
            return redirect()->back()->with('success', __('Work experience saved and synced with Enrollment system.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('System Error: ') . $e->getMessage());
        }
    }
    
    // ... Repeat trigger logic in the update() method ...
}