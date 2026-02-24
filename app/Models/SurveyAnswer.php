<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    // 1. Allow mass assignment for these specific columns
    protected $fillable = [
        'survey_id',
        'question_id',
        'user_id',
        'answer_text'
    ];

    // 2. Define Relationships (We will need these for the Admin Results page next!)
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}