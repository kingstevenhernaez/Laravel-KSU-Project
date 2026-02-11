<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'job_post_id', 'status', 'cover_letter'];

    // Relationship: An application belongs to one Alumni (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: An application belongs to one Job Post
    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
}