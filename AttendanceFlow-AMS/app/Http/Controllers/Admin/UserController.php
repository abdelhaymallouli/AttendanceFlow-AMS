<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Services\UserManagementService;
use App\Services\AcademicService;

class UserController extends Controller
{
    protected UserManagementService $userService;
    protected AcademicService $academicService;

    public function __construct(UserManagementService $userService, AcademicService $academicService)
    {
        $this->userService = $userService;
        $this->academicService = $academicService;
    }

    // ─── Unified Index (Admin) ──────────────────────────────

    public function index(\Illuminate\Http\Request $request)
    {
        $users = $this->userService->getAllUsers($request->search, $request->role);
        $activeRole = $request->role ?? 'all';

        return view('admin.users.index', compact('users', 'activeRole'));
    }

    // ─── Create Forms ───────────────────────────────────────

    public function createStudent()
    {
        $groups = $this->academicService->getGroups();
        return view('admin.users.create', ['role' => 'student', 'groups' => $groups]);
    }

    public function storeStudent(StoreStudentRequest $request)
    {
        $user = $this->userService->createStudent($request->validated());
        return redirect()->route('admin.users.show', $user->id)->with('success', 'Étudiant créé avec succès.');
    }

    public function createTeacher()
    {
        return view('admin.users.create', ['role' => 'teacher', 'groups' => collect()]);
    }

    public function storeTeacher(StoreTeacherRequest $request)
    {
        $user = $this->userService->createTeacher($request->validated());
        return redirect()->route('admin.users.show', $user->id)->with('success', 'Formateur créé avec succès.');
    }

    public function show(int $id)
    {
        $user = \App\Models\User::with(['studentProfile.group.filiere', 'studentProfile.justifications.session.module', 'teacherProfile.modules', 'teacherProfile.groups.filiere', 'roles'])->findOrFail($id);
        $role = $user->getRoleNames()->first();

        $stats = null;
        if ($role === 'student' && $user->studentProfile) {
            $sp = $user->studentProfile;
            $totalSessions = \App\Models\Session::where('group_id', $sp->group_id)->count();
            $present = $sp->attendanceRecords->whereIn('status', ['present', 'late'])->count();
            $absent = $sp->attendanceRecords->whereIn('status', ['absent_unexcused', 'absent_excused'])->count();
            $pending = $sp->justifications->where('status', 'pending')->count();
            $stats = [
                'totalSessions' => $totalSessions,
                'present' => $present,
                'absent' => $absent,
                'attendanceRate' => $totalSessions > 0 ? round(($present / $totalSessions) * 100) : 0,
                'pendingJustifications' => $pending,
            ];
        }

        return view('admin.users.show', compact('user', 'role', 'stats'));
    }

    public function edit(int $id)
    {
        $user = \App\Models\User::with(['studentProfile', 'teacherProfile', 'roles'])->findOrFail($id);
        $role = $user->getRoleNames()->first();
        $groups = $this->academicService->getGroups();

        return view('admin.users.edit', compact('user', 'role', 'groups'));
    }

    public function update(\Illuminate\Http\Request $request, int $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $role = $user->getRoleNames()->first();

        if ($role === 'student') {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $id,
                'phone'    => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
                'matricule' => 'required|string|unique:student_profiles,matricule,' . $user->studentProfile->id . ',id',
                'group_id'  => 'required|exists:groups,id',
            ]);
            $this->userService->updateStudent($id, $validated);
        } elseif ($role === 'teacher') {
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email,' . $id,
                'phone'     => 'nullable|string|max:20',
                'password'  => 'nullable|string|min:6|confirmed',
                'specialty' => 'nullable|string|max:255',
            ]);
            $this->userService->updateTeacher($id, $validated);
        } else {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $id,
                'phone'    => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
            ]);
            $this->userService->updateAdmin($id, $validated);
        }

        return redirect()->route('admin.users.show', $id)->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(int $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
