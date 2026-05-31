<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id', 
        'session_id', 
        'status', 
        'date',
        'justification_id'
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function justification()
    {
        return $this->belongsTo(Justification::class);
    }
}
