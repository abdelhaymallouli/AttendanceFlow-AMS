<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'academic_sessions';

    protected $fillable = [
        'module_id',
        'teacher_profile_id',
        'group_id',
        'start_time',
        'end_time',
        'duration_hours',
        'type',
        'is_published',
        'room',
    ];

    protected $casts = [
        'start_time'   => 'datetime',
        'end_time'     => 'datetime',
        'is_published' => 'boolean',
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

    public function changeRequests()
    {
        return $this->hasMany(TimetableChangeRequest::class);
    }

    /** Only sessions visible to students. */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeInWeek(Builder $q, $start, $end): Builder
    {
        return $q->whereBetween('start_time', [$start, $end]);
    }
}
