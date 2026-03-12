<?php

namespace App\Services;

use App\Models\Filiere;
use App\Models\Group;
use App\Models\Module;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\DB;

/**
 * AcademicService
 * 
 * Manages the structural academic hierarchy and user profiles.
 */
class AcademicService extends BaseService
{
    public function getServiceName(): string
    {
        return 'AcademicService';
    }

    /**
     * Enroll a user as a student in a specific group.
     */
    public function enrollStudent(int $userId, int $groupId, string $matricule): StudentProfile
    {
        $this->logInfo("Enrolling user ID {$userId} into group {$groupId}");
        
        return StudentProfile::updateOrCreate(
            ['user_id' => $userId],
            ['group_id' => $groupId, 'matricule' => $matricule]
        );
    }

    /**
     * Register a user as a teacher.
     */
    public function registerTeacher(int $userId, string $specialty): TeacherProfile
    {
        $this->logInfo("Registering user ID {$userId} as a teacher");
        
        return TeacherProfile::updateOrCreate(
            ['user_id' => $userId],
            ['specialty' => $specialty]
        );
    }

    /**
     * Assign a teacher to a specific module and group.
     */
    public function assignTeacherToModuleAndGroup(int $teacherProfileId, int $moduleId, int $groupId): bool
    {
        $this->logInfo("Assigning teacher {$teacherProfileId} to module {$moduleId} for group {$groupId}");
        
        return DB::table('module_teacher_group')->insertOrIgnore([
            'teacher_profile_id' => $teacherProfileId,
            'module_id' => $moduleId,
            'group_id' => $groupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get the complete academic hierarchy (Filieres -> Groups).
     */
    public function getAcademicHierarchy()
    {
        return Filiere::with('groups')->get();
    }
}
