<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'student_profile_id', 
        'session_id', 
        'status', 
        'date'
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
