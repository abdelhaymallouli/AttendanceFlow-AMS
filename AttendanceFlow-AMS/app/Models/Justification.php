<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    protected $fillable = [
        'student_profile_id', 
        'reason', 
        'file_path', 
        'start_date', 
        'end_date', 
        'status', 
        'submitted_at'
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
