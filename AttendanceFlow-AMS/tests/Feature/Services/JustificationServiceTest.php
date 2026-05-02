<?php

namespace Tests\Feature\Services;

use App\Models\AttendanceRecord;
use App\Models\Justification;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Services\JustificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JustificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JustificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JustificationService();
    }

    public function test_it_can_submit_a_justification_as_pending(): void
    {
        $student = StudentProfile::factory()->create();

        $data = [
            'reason'     => 'Medical appointment',
            'start_date' => '2026-03-10',
            'end_date'   => '2026-03-11',
        ];

        $justification = $this->service->submitJustification($student->id, $data);

        $this->assertInstanceOf(Justification::class, $justification);
        $this->assertEquals('pending', $justification->status);
        $this->assertEquals($student->id, $justification->student_profile_id);

        $this->assertDatabaseHas('justifications', [
            'student_profile_id' => $student->id,
            'status'             => 'pending',
            'reason'             => 'Medical appointment',
        ]);
    }

    public function test_it_can_accept_a_justification_and_update_attendance(): void
    {
        $student       = StudentProfile::factory()->create();
        $session       = Session::factory()->create();
        $justification = Justification::factory()->create([
            'student_profile_id' => $student->id,
            'status'             => 'pending',
            'start_date'         => '2026-03-10',
            'end_date'           => '2026-03-12',
        ]);

        // Create absent records within the justification date range
        AttendanceRecord::factory()->create([
            'student_profile_id' => $student->id,
            'session_id'         => $session->id,
            'status'             => 'absent',
            'date'               => '2026-03-10',
        ]);
        AttendanceRecord::factory()->create([
            'student_profile_id' => $student->id,
            'session_id'         => $session->id,
            'status'             => 'absent',
            'date'               => '2026-03-11',
        ]);

        // Create a record outside the range (should NOT be updated)
        AttendanceRecord::factory()->create([
            'student_profile_id' => $student->id,
            'session_id'         => $session->id,
            'status'             => 'absent',
            'date'               => '2026-03-15',
        ]);

        $result = $this->service->reviewJustification($justification->id, 'accepted');

        $this->assertEquals('accepted', $result->status);

        // Records inside the range should now be 'justified'
        $this->assertDatabaseHas('attendance_records', ['date' => '2026-03-10', 'status' => 'justified']);
        $this->assertDatabaseHas('attendance_records', ['date' => '2026-03-11', 'status' => 'justified']);

        // Record outside the range must remain 'absent'
        $this->assertDatabaseHas('attendance_records', ['date' => '2026-03-15', 'status' => 'absent']);
    }

    public function test_it_can_reject_a_justification_without_touching_attendance(): void
    {
        $student       = StudentProfile::factory()->create();
        $session       = Session::factory()->create();
        $justification = Justification::factory()->create([
            'student_profile_id' => $student->id,
            'status'             => 'pending',
            'start_date'         => '2026-03-10',
            'end_date'           => '2026-03-12',
        ]);

        AttendanceRecord::factory()->create([
            'student_profile_id' => $student->id,
            'session_id'         => $session->id,
            'status'             => 'absent',
            'date'               => '2026-03-10',
        ]);

        $result = $this->service->reviewJustification($justification->id, 'rejected');

        $this->assertEquals('rejected', $result->status);

        // Attendance record must remain 'absent'
        $this->assertDatabaseHas('attendance_records', ['date' => '2026-03-10', 'status' => 'absent']);
    }
    public function test_it_fails_to_review_non_existent_justification(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->service->reviewJustification(9999, 'accepted');
    }
}
