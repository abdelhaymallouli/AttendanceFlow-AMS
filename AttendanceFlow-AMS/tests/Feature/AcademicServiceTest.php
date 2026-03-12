<?php

namespace Tests\Feature;

use App\Models\Filiere;
use App\Models\Group;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\AcademicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AcademicService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AcademicService();
    }

    public function test_it_can_enroll_a_student()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $matricule = 'STD123';

        $profile = $this->service->enrollStudent($user->id, $group->id, $matricule);

        $this->assertInstanceOf(StudentProfile::class, $profile);
        $this->assertEquals($user->id, $profile->user_id);
        $this->assertEquals($group->id, $profile->group_id);
        $this->assertEquals($matricule, $profile->matricule);
        
        $this->assertDatabaseHas('student_profiles', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'matricule' => $matricule,
        ]);
    }

    public function test_it_can_register_a_teacher()
    {
        $user = User::factory()->create();
        $specialty = 'Computer Science';

        $profile = $this->service->registerTeacher($user->id, $specialty);

        $this->assertInstanceOf(TeacherProfile::class, $profile);
        $this->assertEquals($user->id, $profile->user_id);
        $this->assertEquals($specialty, $profile->specialty);

        $this->assertDatabaseHas('teacher_profiles', [
            'user_id' => $user->id,
            'specialty' => $specialty,
        ]);
    }

    public function test_it_can_assign_teacher_to_module_and_group()
    {
        $teacher = TeacherProfile::factory()->create();
        $module = \App\Models\Module::factory()->create();
        $group = Group::factory()->create();

        $result = $this->service->assignTeacherToModuleAndGroup($teacher->id, $module->id, $group->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('module_teacher_group', [
            'teacher_profile_id' => $teacher->id,
            'module_id' => $module->id,
            'group_id' => $group->id,
        ]);
    }

    public function test_it_can_get_academic_hierarchy()
    {
        $filiere = Filiere::factory()->has(Group::factory()->count(2))->create();

        $hierarchy = $this->service->getAcademicHierarchy();

        $this->assertCount(1, $hierarchy);
        $this->assertEquals($filiere->id, $hierarchy->first()->id);
        $this->assertCount(2, $hierarchy->first()->groups);
    }
}
