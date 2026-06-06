<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\Qr\QrTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QrAttendanceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'qr_attendance.hmac.secret' => 'test-secret',
            'qr_attendance.campus.latitude' => 33.5731,
            'qr_attendance.campus.longitude' => -7.5898,
            'qr_attendance.geolocation.max_distance_meters' => 50,
            'qr_attendance.geolocation.accuracy_max_meters' => 100,
            'qr_attendance.wifi.allowed_subnets' => ['10.0.0.0/24'],
            'qr_attendance.wifi.strict' => false,
            'qr_attendance.device.enabled' => true,
            'qr_attendance.validation.weights' => [
                'hmac' => 100, 'geolocation' => 40, 'wifi' => 30, 'device' => 30,
            ],
            'qr_attendance.validation.thresholds' => [
                'present_min' => 70, 'late_min' => 40,
            ],
        ]);
    }

    public function test_token_endpoint_requires_authentication(): void
    {
        $session = Session::factory()->create();
        $this->getJson("/api/attendance/qr/token/{$session->id}")->assertStatus(401);
    }

    public function test_token_endpoint_returns_token_for_teacher(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');
        $teacherProfile = \App\Models\TeacherProfile::create(['user_id' => $teacher->id]);
        $session = Session::factory()->create(['teacher_profile_id' => $teacherProfile->id]);
        Sanctum::actingAs($teacher);

        $res = $this->getJson("/api/attendance/qr/token/{$session->id}");

        $res->assertOk()->assertJsonStructure(['token', 'issued_at', 'expires_at', 'session_id']);
    }

    public function test_student_can_scan_and_get_present(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::create(['user_id' => $student->id]);

        $session = Session::factory()->create();
        $token = (new QrTokenService())->generate($session);

        Sanctum::actingAs($student);

        $res = $this->postJson('/api/attendance/qr/scan', [
            'token' => $token['token'],
            'latitude' => 33.57311,
            'longitude' => -7.58981,
            'accuracy' => 20.0,
        ]);

        $res->assertOk()
            ->assertJsonStructure(['status', 'score', 'max_score', 'signals', 'attendance', 'server_time']);
    }

    public function test_student_cannot_scan_invalid_token(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::create(['user_id' => $student->id]);
        Sanctum::actingAs($student);

        $res = $this->postJson('/api/attendance/qr/scan', [
            'token' => 'invalid.payload.signature',
            'latitude' => 33.57311,
            'longitude' => -7.58981,
        ]);

        $res->assertOk()
            ->assertJsonPath('status', 'rejected')
            ->assertJsonPath('rejection_reason', 'invalid_or_expired_qr_token');
    }

    public function test_scan_requires_authentication(): void
    {
        $this->postJson('/api/attendance/qr/scan', [
            'token' => 'x',
            'latitude' => 0,
            'longitude' => 0,
        ])->assertStatus(401);
    }

    public function test_offline_sync_endpoint_validates_payload(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');
        Sanctum::actingAs($student);

        $this->postJson('/api/attendance/qr/sync-offline', [
            'entries' => [],
        ])->assertStatus(422);
    }
}
