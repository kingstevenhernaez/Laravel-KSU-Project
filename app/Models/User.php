<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'uuid',
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'suffix_name',
        'name',
        'email',
        'password',
        'mobile',
        'birthdate',
        'address',
        'course',
        'department', // This column conflicts with the function below if not fixed
        'year_graduated',
        'role',            
        'status',
        'is_alumni',
        'force_password_change',
        'image',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // 🟢 RENAMED to 'departmentRel' to prevent crash
    public function departmentRel()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}