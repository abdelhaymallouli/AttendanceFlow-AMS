<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id', 
        'session_id',
        'reason', 
        'document_name', 
        'start_date', 
        'end_date', 
        'status', 
        'submitted_at',
        'reviewed_at',
        'reviewed_by'
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
