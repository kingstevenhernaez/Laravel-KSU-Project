<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsuAlumniRecord extends Model
{
    protected $table = 'ksu_alumni_records';

    protected $fillable = [
        'tenant_id',
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'mobile',
        'program_code',
        'program_name',
        'college_name',
        'graduation_year',
        'payload',
        'claimed_at',
        'claimed_user_id',
        'claim_status',
    ];

    protected $casts = [
        'payload' => 'array',
        'claimed_at' => 'datetime',
    ];
}
