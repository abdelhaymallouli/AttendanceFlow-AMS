<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use App\Models\TeacherProfile;
use App\Services\SchedulingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SchedulingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SchedulingService();
    }

    private function makeSessionData(TeacherProfile $teacher, string $start, string $end): array
    {
        return [
            'teacher_profile_id' => $teacher->id,
            'module_id'          => Module::factory()->create()->id,
            'group_id'           => Group::factory()->create()->id,
            'start_time'         => $start,
            'end_time'           => $end,
        ];
    }

    public function test_it_can_schedule_a_new_session(): void
    {
        $teacher = TeacherProfile::factory()->create();

        $data    = $this->makeSessionData($teacher, '2026-03-13 08:00:00', '2026-03-13 10:00:00');
        $session = $this->service->scheduleSession($data);

        $this->assertInstanceOf(Session::class, $session);
        $this->assertDatabaseHas('academic_sessions', [
            'teacher_profile_id' => $teacher->id,
        ]);
    }

    public function test_it_prevents_scheduling_when_teacher_has_a_conflict(): void
    {
        $teacher = TeacherProfile::factory()->create();

        // Schedule an initial session
        $this->service->scheduleSession(
            $this->makeSessionData($teacher, '2026-03-13 08:00:00', '2026-03-13 10:00:00')
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The teacher is already busy during this time.');

        // Schedule overlapping session for the same teacher
        $this->service->scheduleSession(
            $this->makeSessionData($teacher, '2026-03-13 09:00:00', '2026-03-13 11:00:00')
        );
    }

    public function test_it_allows_non_overlapping_sessions_for_same_teacher(): void
    {
        $teacher = TeacherProfile::factory()->create();

        $this->service->scheduleSession(
            $this->makeSessionData($teacher, '2026-03-13 08:00:00', '2026-03-13 10:00:00')
        );

        // This session starts after the previous one ends so it should NOT conflict
        $session = $this->service->scheduleSession(
            $this->makeSessionData($teacher, '2026-03-13 10:00:00', '2026-03-13 12:00:00')
        );

        $this->assertInstanceOf(Session::class, $session);
        $this->assertDatabaseCount('academic_sessions', 2);
    }

    public function test_it_can_get_group_schedule_within_date_range(): void
    {
        $group   = Group::factory()->create();
        $teacher = TeacherProfile::factory()->create();
        $module  = Module::factory()->create();

        // Session within range
        Session::factory()->create([
            'group_id'           => $group->id,
            'teacher_profile_id' => $teacher->id,
            'module_id'          => $module->id,
            'start_time'         => '2026-03-13 08:00:00',
            'end_time'           => '2026-03-13 10:00:00',
        ]);

        // Session outside range
        Session::factory()->create([
            'group_id'           => $group->id,
            'teacher_profile_id' => $teacher->id,
            'module_id'          => $module->id,
            'start_time'         => '2026-04-01 08:00:00',
            'end_time'           => '2026-04-01 10:00:00',
        ]);

        $start    = Carbon::parse('2026-03-01');
        $end      = Carbon::parse('2026-03-31');
        $schedule = $this->service->getGroupSchedule($group->id, $start, $end);

        $this->assertCount(1, $schedule);
        $this->assertEquals($group->id, $schedule->first()->group_id);
    }

    public function test_it_returns_empty_collection_when_no_sessions_in_range(): void
    {
        $group    = Group::factory()->create();
        $start    = Carbon::parse('2026-01-01');
        $end      = Carbon::parse('2026-01-31');
        $schedule = $this->service->getGroupSchedule($group->id, $start, $end);

        $this->assertCount(0, $schedule);
    }
}
