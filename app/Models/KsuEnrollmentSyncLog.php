<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsuEnrollmentSyncLog extends Model
{
    protected $table = 'ksu_enrollment_sync_logs';

    protected $fillable = [
        'tenant_id',
        'started_at',
        'finished_at',
        'inserted',
        'updated',
        'failed',
        'error_summary',
        'meta',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'meta' => 'array',
    ];
}
