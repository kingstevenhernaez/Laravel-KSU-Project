<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurveyQuestion extends Model
{
    protected $table = 'tracer_survey_questions';

    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function survey()
    {
        return $this->belongsTo(TracerSurvey::class, 'survey_id');
    }

    public function options()
    {
        return $this->hasMany(TracerSurveyOption::class, 'question_id')->orderBy('sort_order');
    }
}
