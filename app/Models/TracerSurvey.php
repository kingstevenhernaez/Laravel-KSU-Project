<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerSurvey extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship: A Survey has many Answers
    public function answers()
    {
        return $this->hasMany(TracerAnswer::class);
    }
}