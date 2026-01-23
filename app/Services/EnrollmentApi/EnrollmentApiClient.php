<?php

namespace App\Services\EnrollmentApi;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class EnrollmentApiClient
{
    public function __construct(
        private readonly ?string $baseUrl = null,
        private readonly ?string $apiKey = null,
        private readonly int $timeout = 20,
    ) {}

    /**
     * Fetch graduates from the mock enrollment API.
     *
     * The reference Mock Enrollment ZIP uses:
     * - Endpoint: GET /api/graduates
     * - Header:  X-API-Key: <secret>
     */
    public function fetchGraduates(): array
    {
        $base = $this->baseUrl ?? config('services.enrollment_api.base_url');
        $key  = $this->apiKey  ?? config('services.enrollment_api.key');
        $timeout = $this->timeout ?: (int) config('services.enrollment_api.timeout', 20);

        if (!$base) {
            throw new \RuntimeException('ENROLLMENT_API_BASE_URL is not configured.');
        }
        if (!$key) {
            throw new \RuntimeException('ENROLLMENT_API_KEY is not configured.');
        }

        $base = rtrim($base, '/');

        // Allow either:
        // - ENROLLMENT_API_BASE_URL=http://host:port (we call /api/graduates)
        // - ENROLLMENT_API_BASE_URL=http://host:port/api (we call /graduates)
        $endpoint = str_ends_with($base, '/api') ? ($base . '/graduates') : ($base . '/api/graduates');

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'X-API-Key' => $key,
                    'Accept' => 'application/json',
                ])
                ->get($endpoint);
        } catch (ConnectionException $e) {
            throw new \RuntimeException('Enrollment API connection failed: ' . $e->getMessage(), 0, $e);
        }

        if ($response->failed()) {
            $msg = 'Enrollment API request failed with status ' . $response->status();
            $body = $response->json();
            if (is_array($body) && isset($body['message'])) {
                $msg .= ': ' . $body['message'];
            }
            throw new \RuntimeException($msg);
        }

        $payload = $response->json();

        // Mock API shape:
        // { "data": [ { student_number, first_name, ... } ] }
        if (!is_array($payload) || !isset($payload['data']) || !is_array($payload['data'])) {
            throw new \RuntimeException('Enrollment API response format is invalid.');
        }

        return $payload['data'];
    }
}
