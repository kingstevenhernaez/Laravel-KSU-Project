<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'tracking_code', 
        'document_type', 
        'copies', 
        'purpose', 
        'status', 
        'remarks'
    ];

    // A request belongs to an Alumni (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}