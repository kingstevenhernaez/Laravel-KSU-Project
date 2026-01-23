<?php

namespace App\Services\EnrollmentApi;

use App\Models\KsuAlumniRecord;
use App\Models\KsuEnrollmentSyncLog;
use Illuminate\Support\Facades\Log;

class AlumniSyncService
{
    protected $client;

    public function __construct(EnrollmentApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * PULL: Sync graduated students from Mock Enrollment to Alumni System.
     */
    public function syncGraduatedStudents()
    {
        // 1. Fetch data from Mock API
        $students = $this->client->getGraduatedStudents();

        if (empty($students)) {
            Log::info('No new graduates to sync.');
            return 0;
        }

        $count = 0;
        foreach ($students as $data) {
            // 2. Map and Save/Update Record
            KsuAlumniRecord::updateOrCreate(
                ['student_number' => $data['student_id']], // Unique ID
                [
                    'first_name'      => $data['first_name'],
                    'last_name'       => $data['last_name'],
                    'email'           => $data['email'],
                    'birthdate'       => $data['birthdate'], // Format: YYYY-MM-DD
                    'graduation_year' => $data['year'],
                    'department_code' => $data['dept'],
                    'tenant_id'       => getTenantId(),
                ]
            );
            $count++;
        }

        // Log the activity
        KsuEnrollmentSyncLog::create([
            'synced_count' => $count,
            'status'       => 'success',
            'tenant_id'    => getTenantId(),
        ]);

        return $count;
    }

    /**
     * PUSH: Automatically save Job History back to Mock Enrollment System.
     */
    public function updateJobInEnrollment($studentId, $jobData)
    {
        try {
            // Enrollment system ONLY accepts job updates
            $response = $this->client->pushJobUpdate($studentId, $jobData);
            
            if ($response) {
                Log::info("Job synced back to Mock Enrollment for student: {$studentId}");
                return true;
            }
        } catch (\Exception $e) {
            Log::error("Mock API Sync Failure: " . $e->getMessage());
        }

        return false;
    }
}