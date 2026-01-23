<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurveyOption extends Model
{
    protected $table = 'tracer_survey_options';

    protected $fillable = [
        'question_id',
        'option_text',
        'sort_order',
    ];

    public function question()
    {
        return $this->belongsTo(TracerSurveyQuestion::class, 'question_id');
    }
}
