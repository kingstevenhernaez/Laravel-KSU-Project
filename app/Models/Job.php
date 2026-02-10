<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];

    // Helper to color-code job types
    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'Full-time' => 'success',
            'Part-time' => 'warning',
            'Contract'  => 'info',
            'Remote'    => 'primary',
            default     => 'secondary',
        };
    }
}