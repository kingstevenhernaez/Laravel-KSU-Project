<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KsuAlumniRecord extends Model
{
    use HasFactory;

    protected $table = 'ksu_alumni_records';

    // FIX: This list allows the data to be written to your database
    protected $fillable = [
        'tenant_id',
        'student_number',
        'first_name',
        'last_name',
        'email',
        'birthdate',
        'graduation_year',
        'department_code',
        'program_code',
        'mobile',
        'claimed_user_id',
        'claimed_at'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'claimed_at' => 'datetime',
    ];
}