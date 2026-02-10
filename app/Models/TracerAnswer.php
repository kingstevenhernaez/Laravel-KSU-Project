<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship: An Answer belongs to a User (Alumni)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: An Answer belongs to a Survey
    public function survey()
    {
        return $this->belongsTo(TracerSurvey::class, 'tracer_survey_id');
    }
}