<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $fillable = ['subject', 'message', 'recipient_email', 'sent_at'];
    
    // 🟢 Add this block
    protected $casts = [
        'sent_at' => 'datetime',
    ];
    
    use HasFactory;
}
