<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    // 🟢 1. Point to the correct table
    protected $table = 'job_posts'; 

    // 🟢 2. Allow these columns to be saved (Security setting)
    protected $fillable = [
        'title',
        'company',    // Matches database column
        'location',
        'type',       // Matches database column
        'description',
        'salary',
        'deadline',
        'is_active',  // Matches database column
        'link',
        'contact_email'
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id');
    }
}