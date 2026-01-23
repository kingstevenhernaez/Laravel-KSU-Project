<?php

namespace App\Services\EnrollmentApi;

use App\Models\KsuAlumniRecord;
use App\Models\KsuEnrollmentSyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlumniSyncService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('KSU_ENROLLMENT_API_BASE_URL');
        $this->apiKey = env('KSU_ENROLLMENT_API_KEY');
    }

    /**
     * REAL SYNC: Connects to the Mock API
     */
    public function syncGraduatedStudents()
    {
        // 1. Call the Real API
        $response = Http::withHeaders(['X-API-KEY' => $this->apiKey])
            ->timeout(5) // Wait up to 5 seconds
            ->get("{$this->baseUrl}/graduates");

        // 2. Check for Errors
        if ($response->failed()) {
            $errorMsg = $response->body() ?: $response->status();
            throw new \Exception("Failed to connect to Mock API at {$this->baseUrl}. Error: $errorMsg");
        }

        $students = $response->json();
        
        if (empty($students)) {
            return 0; // Connection worked, but no students found
        }

        // 3. Save Data to Database
        $count = 0;
        foreach ($students as $data) {
            KsuAlumniRecord::updateOrCreate(
                ['student_number' => $data['student_id']],
                [
                    'first_name'      => $data['first_name'],
                    'last_name'       => $data['last_name'],
                    'email'           => $data['email'],
                    'birthdate'       => $data['birthdate'],
                    'graduation_year' => $data['year'],
                    'department_code' => $data['dept'],
                    'tenant_id'       => 1, // Force Tenant ID 1
                ]
            );
            $count++;
        }

        // 4. Log Success
        KsuEnrollmentSyncLog::create([
            'synced_count' => $count,
            'status'       => 'success',
            'tenant_id'    => 1,
        ]);

        return $count;
    }

    public function updateJobInEnrollment($studentId, $jobData)
    {
        // This is for the Bidirectional Sync later
        $response = Http::withHeaders(['X-API-KEY' => $this->apiKey])
             ->post("{$this->baseUrl}/update-job", [
                'student_id' => $studentId,
                'company' => $jobData['company'],
                'position' => $jobData['position'],
                'start_date' => $jobData['start_date'],
            ]);
            
        return $response->successful();
    }
}