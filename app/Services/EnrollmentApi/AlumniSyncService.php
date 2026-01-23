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

    public function syncGraduatedStudents()
    {
        // 1. Call the API
        $response = Http::withHeaders(['X-API-KEY' => $this->apiKey])
            ->timeout(5)
            ->get("{$this->baseUrl}/graduates");

        if ($response->failed()) {
            throw new \Exception("Connection Failed: " . $response->status());
        }

        $json = $response->json();
        
        // Open the "data" wrapper if it exists
        $students = isset($json['data']) ? $json['data'] : $json;

        $count = 0;

        foreach ($students as $studentData) {
            // FIX 1: The Mock App uses 'student_number', not 'student_id'
            $studentId = $studentData['student_number'] ?? $studentData['student_id'] ?? null;

            // If we still can't find an ID, skip this row
            if (!$studentId) continue;

            KsuAlumniRecord::updateOrCreate(
                ['student_number' => $studentId],
                [
                    'first_name'      => $studentData['first_name'],
                    'last_name'       => $studentData['last_name'],
                    'email'           => $studentData['email'],
                    'birthdate'       => $studentData['birthdate'],
                    
                    // FIX 2: Map 'year_graduated' correctly
                    'graduation_year' => $studentData['year_graduated'] ?? $studentData['year'] ?? null,
                    
                    // FIX 3: Map 'college_name' to department
                    'department_code' => $studentData['college_name'] ?? $studentData['dept'] ?? 'N/A',
                    
                    'tenant_id'       => 1, 
                ]
            );
            $count++;
        }

        KsuEnrollmentSyncLog::create([
            'synced_count' => $count,
            'status'       => 'success',
            'tenant_id'    => 1,
        ]);

        return $count;
    }

    public function updateJobInEnrollment($studentId, $jobData)
    {
        return Http::withHeaders(['X-API-KEY' => $this->apiKey])
             ->post("{$this->baseUrl}/update-job", [
                'student_id' => $studentId, // Note: Ensure this matches your endpoint expectation if you use it later
                'company' => $jobData['company'],
                'position' => $jobData['position'],
                'start_date' => $jobData['start_date'],
            ])->successful();
    }
}