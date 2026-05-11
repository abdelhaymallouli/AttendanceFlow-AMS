<?php

namespace Tests\Feature\Services;

use App\Models\AttendanceRecord;
use App\Models\Group;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Services\ReportingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReportingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReportingService();
    }

    public function test_it_can_generate_a_group_report_with_correct_totals(): void
    {
        $group   = Group::factory()->create();
        $session = Session::factory()->create();
        $students = StudentProfile::factory()->count(4)->create(['group_id' => $group->id]);

        $startDate = '2026-03-01';
        $endDate   = '2026-03-31';

        // 2 present, 1 absent, 1 justified
        AttendanceRecord::factory()->create(['student_profile_id' => $students[0]->id, 'session_id' => $session->id, 'date' => '2026-03-05', 'status' => 'present']);
        AttendanceRecord::factory()->create(['student_profile_id' => $students[1]->id, 'session_id' => $session->id, 'date' => '2026-03-06', 'status' => 'present']);
        AttendanceRecord::factory()->create(['student_profile_id' => $students[2]->id, 'session_id' => $session->id, 'date' => '2026-03-07', 'status' => 'absent']);
        AttendanceRecord::factory()->create(['student_profile_id' => $students[3]->id, 'session_id' => $session->id, 'date' => '2026-03-08', 'status' => 'justified']);

        $report = $this->service->generateGroupReport($group->id, $startDate, $endDate);

        $this->assertIsArray($report);
        $this->assertEquals(4, $report['total_records']);
        $this->assertEquals(2, $report['present']);
        $this->assertEquals(2, $report['absent_total']); // absent + justified
        $this->assertEquals('50%', $report['attendance_rate']);
    }

    public function test_it_returns_zero_rate_when_no_records_exist(): void
    {
        $group = Group::factory()->create();

        $report = $this->service->generateGroupReport($group->id, '2026-01-01', '2026-01-31');

        $this->assertEquals(0, $report['total_records']);
        $this->assertEquals(0, $report['present']);
        $this->assertEquals(0, $report['absent_total']);
        $this->assertEquals('0%', $report['attendance_rate']);
    }

    public function test_it_excludes_records_outside_date_range(): void
    {
        $group   = Group::factory()->create();
        $session = Session::factory()->create();
        $student = StudentProfile::factory()->create(['group_id' => $group->id]);

        // Record inside range
        AttendanceRecord::factory()->create(['student_profile_id' => $student->id, 'session_id' => $session->id, 'date' => '2026-03-15', 'status' => 'present']);

        // Record outside range — should be ignored
        AttendanceRecord::factory()->create(['student_profile_id' => $student->id, 'session_id' => $session->id, 'date' => '2026-02-20', 'status' => 'present']);

        $report = $this->service->generateGroupReport($group->id, '2026-03-01', '2026-03-31');

        $this->assertEquals(1, $report['total_records']);
    }

    public function test_it_excludes_records_from_other_groups(): void
    {
        $group1   = Group::factory()->create();
        $group2   = Group::factory()->create();
        $session  = Session::factory()->create();
        $student1 = StudentProfile::factory()->create(['group_id' => $group1->id]);
        $student2 = StudentProfile::factory()->create(['group_id' => $group2->id]);

        AttendanceRecord::factory()->create(['student_profile_id' => $student1->id, 'session_id' => $session->id, 'date' => '2026-03-10', 'status' => 'present']);
        AttendanceRecord::factory()->create(['student_profile_id' => $student2->id, 'session_id' => $session->id, 'date' => '2026-03-10', 'status' => 'present']);

        $report = $this->service->generateGroupReport($group1->id, '2026-03-01', '2026-03-31');

        // Only group1's student should be counted
        $this->assertEquals(1, $report['total_records']);
    }
}
