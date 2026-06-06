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
        'justification_id',
        'check_in_method',
        'latitude',
        'longitude',
        'distance_meters',
        'wifi_ip',
        'device_fingerprint_id',
        'qr_token_id',
        'synced_at',
        'validation_score',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'distance_meters' => 'integer',
        'validation_score' => 'integer',
        'synced_at' => 'datetime',
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

    public function deviceFingerprint()
    {
        return $this->belongsTo(DeviceFingerprint::class);
    }

    public function qrToken()
    {
        return $this->belongsTo(QrAttendanceToken::class, 'qr_token_id');
    }
}
