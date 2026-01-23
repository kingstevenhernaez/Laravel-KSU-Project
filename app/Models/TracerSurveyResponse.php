<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurveyResponse extends Model
{
    protected $table = 'tracer_survey_responses';

    protected $fillable = [
        'tenant_id',
        'survey_id',
        'alumni_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(TracerSurvey::class, 'survey_id');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    public function answers()
    {
        return $this->hasMany(TracerSurveyAnswer::class, 'response_id');
    }
}
