<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementService extends BaseService
{
    // ─── Unified User List ──────────────────────────────────

    public function getAllUsers(?string $search = null, ?string $role = null, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::with(['studentProfile.group.filiere', 'teacherProfile', 'roles']);

        if ($role && in_array($role, ['admin', 'teacher', 'student'])) {
            $query->role($role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', fn($sq) => $sq->where('matricule', 'like', "%{$search}%"));
            });
        }

        return $query->orderBy('name')->paginate($perPage)->withQueryString();
    }

    // ─── Teacher's Students (read-only) ────────────────────

    public function getTeacherStudents(int $teacherProfileId, ?string $search = null, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $teacherProfile = TeacherProfile::findOrFail($teacherProfileId);

        $groupIds = $teacherProfile->groups()->pluck('groups.id');

        $query = StudentProfile::with(['user', 'group.filiere'])
            ->whereIn('group_id', $groupIds);

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('matricule', 'like', "%{$search}%");
        }

        return $query->orderBy('matricule')->paginate($perPage)->withQueryString();
    }

    // ─── Students ───────────────────────────────────────────

    public function getStudents(?string $search = null, ?int $groupId = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = StudentProfile::with(['user', 'group.filiere']);

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('matricule', 'like', "%{$search}%");
        }

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->orderBy('matricule')->paginate($perPage);
    }

    public function getStudent(int $id): StudentProfile
    {
        return StudentProfile::with(['user', 'group.filiere', 'justifications.session.module'])->findOrFail($id);
    }

    public function createStudent(array $data): User
    {
        $this->logInfo("Creating student user: {$data['email']}");

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('student');

            StudentProfile::create([
                'user_id'   => $user->id,
                'matricule' => $data['matricule'],
                'group_id'  => $data['group_id'],
            ]);

            return $user;
        });
    }

    public function updateStudent(int $userId, array $data): User
    {
        $this->logInfo("Updating student user ID: {$userId}");

        return DB::transaction(function () use ($userId, $data) {
            $user = User::findOrFail($userId);

            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            $user->studentProfile->update([
                'matricule' => $data['matricule'],
                'group_id'  => $data['group_id'],
            ]);

            return $user;
        });
    }

    public function deleteStudent(int $userId): bool
    {
        $this->logInfo("Deleting student user ID: {$userId}");
        return User::findOrFail($userId)->delete();
    }

    // ─── Teachers ───────────────────────────────────────────

    public function getTeachers(?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = TeacherProfile::with(['user', 'modules', 'groups']);

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        return $query->paginate($perPage);
    }

    public function getTeacher(int $id): TeacherProfile
    {
        return TeacherProfile::with(['user', 'modules', 'groups.filiere', 'sessions.module', 'sessions.group'])->findOrFail($id);
    }

    public function createTeacher(array $data): User
    {
        $this->logInfo("Creating teacher user: {$data['email']}");

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('teacher');

            TeacherProfile::create([
                'user_id'    => $user->id,
                'specialty'  => $data['specialty'] ?? null,
            ]);

            return $user;
        });
    }

    public function updateTeacher(int $userId, array $data): User
    {
        $this->logInfo("Updating teacher user ID: {$userId}");

        return DB::transaction(function () use ($userId, $data) {
            $user = User::findOrFail($userId);

            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            $user->teacherProfile->update([
                'specialty' => $data['specialty'] ?? null,
            ]);

            return $user;
        });
    }

    public function deleteTeacher(int $userId): bool
    {
        $this->logInfo("Deleting teacher user ID: {$userId}");
        return User::findOrFail($userId)->delete();
    }

    // ─── Admins ─────────────────────────────────────────────

    public function getAdmins(?string $search = null, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::role('admin')->withCount('studentProfile');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }

    public function getAdmin(int $id): User
    {
        return User::role('admin')->findOrFail($id);
    }

    public function updateAdmin(int $id, array $data): User
    {
        $this->logInfo("Updating admin user ID: {$id}");

        $user = User::findOrFail($id);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return $user;
    }
}
