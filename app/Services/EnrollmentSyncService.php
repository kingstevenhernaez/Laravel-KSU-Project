<?php

namespace App\Services;

use App\Models\KsuAlumniRecord;
use App\Models\KsuEnrollmentSyncLog;
use App\Services\EnrollmentApi\EnrollmentApiClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EnrollmentSyncService
{
    public function __construct(private readonly EnrollmentApiClient $client)
    {
    }

    /**
     * Runs the enrollment sync:
     * - Pulls records from the Mock Enrollment API
     * - Upserts into ksu_alumni_records (tenant scoped when tenant is active)
     * - Logs result to ksu_enrollment_sync_logs
     */
    public function run(): KsuEnrollmentSyncLog
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;

        $log = KsuEnrollmentSyncLog::create([
            'tenant_id' => $tenantId,
            'started_at' => Carbon::now(),
            'inserted' => 0,
            'updated' => 0,
            'failed' => 0,
            'error_summary' => null,
            'meta' => [],
        ]);

        $inserted = 0;
        $updated  = 0;
        $failed   = 0;
        $errors   = [];

        try {
            $rows = $this->client->fetchGraduates();

            DB::transaction(function () use ($rows, $tenantId, &$inserted, &$updated, &$failed, &$errors) {
                foreach ($rows as $i => $row) {
                    try {
                        $studentNumber = trim((string) ($row['student_number'] ?? ''));
                        if ($studentNumber === '') {
                            throw new \RuntimeException('Missing student_number');
                        }

                        $payload = [
                            'tenant_id' => $tenantId,
                            'student_number' => $studentNumber,
                            'first_name' => trim((string) ($row['first_name'] ?? '')),
                            'middle_name' => trim((string) ($row['middle_name'] ?? '')),
                            'last_name' => trim((string) ($row['last_name'] ?? '')),
                            'program_name' => trim((string) ($row['program_name'] ?? '')),
                            'program_code' => trim((string) ($row['program_code'] ?? '')),
                            'graduation_year' => (int) ($row['graduation_year'] ?? null),
                        ];

                        // Tenant-aware lookup
                        $query = KsuAlumniRecord::query()->where('student_number', $studentNumber);
                        if (!is_null($tenantId)) {
                            $query->where('tenant_id', $tenantId);
                        }

                        $existing = $query->first();

                        if ($existing) {
                            $existing->fill($payload);
                            $dirty = $existing->isDirty();
                            $existing->save();

                            if ($dirty) {
                                $updated++;
                            }
                        } else {
                            KsuAlumniRecord::create($payload);
                            $inserted++;
                        }
                    } catch (\Throwable $e) {
                        $failed++;
                        $errors[] = 'Row #' . ($i + 1) . ': ' . $e->getMessage();
                    }
                }
            });
        } catch (\Throwable $e) {
            $failed++;
            $errors[] = $e->getMessage();
        }

        $log->update([
            'finished_at' => Carbon::now(),
            'inserted' => $inserted,
            'updated' => $updated,
            'failed' => $failed,
            'error_summary' => $this->firstLines($errors, 6),
            'meta' => [
                'error_count' => count($errors),
            ],
        ]);

        return $log;
    }

    private function firstLines(array $errors, int $maxLines = 5): ?string
    {
        if (!$errors) {
            return null;
        }
        $slice = array_slice($errors, 0, $maxLines);
        return implode("\n", $slice);
    }
}
