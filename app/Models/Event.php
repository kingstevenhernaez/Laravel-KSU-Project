<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // 🟢 ALL columns from your migration must be here
  protected $fillable = [
    'title',
    'slug',
    'description',
    'location',
    'date',            // 🟢 Changed from event_date to date
    'user_id',         // 🟢 Required by your migration
    'event_category_id', // 🟢 Required by your migration
    'thumbnail',
];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    
}