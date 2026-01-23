<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurveyAnswer extends Model
{
    protected $table = 'tracer_survey_answers';

    protected $fillable = [
        'response_id',
        'question_id',
        'answer',
    ];

    public function response()
    {
        return $this->belongsTo(TracerSurveyResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(TracerSurveyQuestion::class, 'question_id');
    }
}
