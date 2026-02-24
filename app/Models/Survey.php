<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['title', 'description', 'is_active', 'is_ched_template', 'created_by'];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order_num');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}