<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurvey extends Model
{
    protected $table = 'tracer_surveys';

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'is_published',
        'target_rules',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'target_rules' => 'array',
    ];

    public function questions()
    {
        return $this->hasMany(TracerSurveyQuestion::class, 'survey_id')->orderBy('sort_order');
    }
}
