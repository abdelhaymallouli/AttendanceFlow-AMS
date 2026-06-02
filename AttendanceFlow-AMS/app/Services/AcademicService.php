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
    // Service name is now automatically handled by BaseService

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
     * Get all students for export.
     */
    public function getStudentsForExport(): \Illuminate\Database\Eloquent\Collection
    {
        return StudentProfile::with('user', 'group')->get();
    }

    /**
     * Get the complete academic hierarchy (Filieres -> Groups).
     */
    public function getAcademicHierarchy()
    {
        return Filiere::with('groups')->get();
    }

    /**
     * Get all filieres.
     */
    public function getFilieres(): \Illuminate\Database\Eloquent\Collection
    {
        return Filiere::all();
    }

    /**
     * Get all groups with filiere relationship.
     */
    public function getGroups(): \Illuminate\Database\Eloquent\Collection
    {
        return Group::with('filiere')->get();
    }

    /**
     * Get all modules.
     */
    public function getModules(): \Illuminate\Database\Eloquent\Collection
    {
        return Module::all();
    }

    /**
     * Get all sessions with core relationships.
     */
    public function getSessions(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Session::with(['group.studentProfiles.user', 'module', 'teacherProfile.user'])->get();
    }

    /**
     * Get sessions for a specific teacher.
     */
    public function getTeacherSessions(int $teacherProfileId): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Session::where('teacher_profile_id', $teacherProfileId)
            ->with(['group.studentProfiles.user', 'module'])
            ->get();
    }

    /**
     * Get a specific session by ID with relationships.
     */
    public function getSession(int $id): \App\Models\Session
    {
        return \App\Models\Session::with(['group.studentProfiles.user', 'module', 'teacherProfile.user'])
            ->findOrFail($id);
    }

    /**
     * Get all teacher profiles with user relationship.
     */
    public function getAllTeacherProfiles(): \Illuminate\Database\Eloquent\Collection
    {
        return TeacherProfile::with('user')->get();
    }
}
