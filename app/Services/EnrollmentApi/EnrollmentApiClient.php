<?php

namespace App\Services\EnrollmentApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnrollmentApiClient
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.enrollment_api.url', 'https://enrollment.ksu.edu.ph/api');
        $this->apiKey = config('services.enrollment_api.key');
    }

    /**
     * Fetch alumni records from the Enrollment System
     * * @param string|null $updatedAfter Date string (Y-m-d)
     * @return array
     */
    public function getAlumniRecords($updatedAfter = null)
    {
        try {
            $params = [];
            if ($updatedAfter) {
                $params['updated_after'] = $updatedAfter;
            }

            // Mocking the request for now if no real endpoint exists
            // Replace with: $response = Http::withToken($this->apiKey)->get($this->baseUrl . '/alumni', $params);
            
            // For now, return empty array to prevent crashes if API isn't live
            return [];
            
        } catch (\Exception $e) {
            Log::error("Enrollment API Error: " . $e->getMessage());
            return [];
        }
    }
}