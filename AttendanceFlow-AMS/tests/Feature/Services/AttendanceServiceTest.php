<?php

namespace Tests\Feature\Services;

use App\Models\AttendanceRecord;
use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AttendanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AttendanceService();
    }

    public function test_it_can_mark_attendance_for_a_student(): void
    {
        $student = StudentProfile::factory()->create();
        $session = Session::factory()->create();
        $date = '2026-03-12';

        $record = $this->service->markAttendance($student->id, $session->id, 'present', $date);

        $this->assertInstanceOf(AttendanceRecord::class, $record);
        $this->assertEquals($student->id, $record->student_profile_id);
        $this->assertEquals($session->id, $record->session_id);
        $this->assertEquals('present', $record->status);
        $this->assertEquals($date, $record->date);

        $this->assertDatabaseHas('attendance_records', [
            'student_profile_id' => $student->id,
            'session_id'         => $session->id,
            'status'             => 'present',
            'date'               => $date,
        ]);
    }

    public function test_marking_attendance_is_idempotent(): void
    {
        $student = StudentProfile::factory()->create();
        $session = Session::factory()->create();
        $date = '2026-03-12';

        // Mark as absent first
        $this->service->markAttendance($student->id, $session->id, 'absent', $date);

        // Update to present
        $record = $this->service->markAttendance($student->id, $session->id, 'present', $date);

        $this->assertEquals('present', $record->status);

        // Must be only one record in DB (updateOrCreate)
        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_it_can_bulk_mark_attendance_for_a_session(): void
    {
        $session  = Session::factory()->create();
        $students = StudentProfile::factory()->count(3)->create();
        $date     = '2026-03-12';

        $attendanceData = $students->map(fn($s) => [
            'student_profile_id' => $s->id,
            'status'             => 'present',
            'date'               => $date,
        ])->toArray();

        $this->service->bulkMarkAttendance($session->id, $attendanceData);

        $this->assertDatabaseCount('attendance_records', 3);

        foreach ($students as $student) {
            $this->assertDatabaseHas('attendance_records', [
                'student_profile_id' => $student->id,
                'session_id'         => $session->id,
                'status'             => 'present',
            ]);
        }
    }

    public function test_it_can_get_session_attendance(): void
    {
        $session  = Session::factory()->create();
        $students = StudentProfile::factory()->count(2)->create();
        $date     = '2026-03-12';

        foreach ($students as $student) {
            AttendanceRecord::factory()->create([
                'student_profile_id' => $student->id,
                'session_id'         => $session->id,
                'date'               => $date,
                'status'             => 'present',
            ]);
        }

        // Records for a different session — should NOT appear
        AttendanceRecord::factory()->create([
            'session_id' => Session::factory()->create()->id,
        ]);

        $records = $this->service->getSessionAttendance($session->id);

        $this->assertCount(2, $records);
        $records->each(fn($r) => $this->assertEquals($session->id, $r->session_id));
    }
}
