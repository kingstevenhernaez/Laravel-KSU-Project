<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MisAlumniRecord extends Model
{
    use HasFactory;

    // 🟢 This array tells Laravel which columns are allowed to be saved via the CSV uploader
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'course',
        'year_graduated',
        'birthdate',
        'is_claimed',
        'user_id'
    ];

    // Link this record to the actual User account once they claim it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}