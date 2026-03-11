<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
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
        'employment_status', 
        'job_title',         
        'company',           
        'birthdate',
        'address',
        'course',
        'department', 
        'year_graduated',
        'role',     
        'role_name',       
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
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function misRecord()
    {
        // Links the 'student_id' in the users table to the 'student_id' in mis_alumni_records
        return $this->hasOne(\App\Models\MisAlumniRecord::class, 'student_id', 'student_id');
    }

    /**
     * Career Timeline Relationship
     */
    public function employmentHistories()
    {
        return $this->hasMany(EmploymentHistory::class)->orderBy('start_date', 'desc');
    }
}