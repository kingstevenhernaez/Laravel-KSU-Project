<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    use HasFactory;

    protected $table = 'alumnus';

  protected $fillable = [
        'user_id', 'id_number', 'department_id', 'batch_id', 'passing_year_id'
    ];
}