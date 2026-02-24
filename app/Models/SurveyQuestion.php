<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = ['survey_id', 'question_text', 'answer_type', 'options', 'order_num', 'is_required'];

    // Tells Laravel to automatically convert the JSON string back into an array
    protected $casts = [
        'options' => 'array', 
    ];
}