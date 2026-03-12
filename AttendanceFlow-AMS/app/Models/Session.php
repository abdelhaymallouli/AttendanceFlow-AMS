<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'academic_sessions';

    protected $fillable = [
        'module_id', 
        'teacher_profile_id', 
        'group_id', 
        'start_time', 
        'end_time', 
        'duration_hours', 
        'type'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function teacherProfile()
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
