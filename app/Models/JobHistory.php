<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    protected $table = 'job_histories';

    protected $fillable = [
        'tenant_id',
        'alumni_id',
        'company_name',
        'job_title',
        'employment_type',
        'industry',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
