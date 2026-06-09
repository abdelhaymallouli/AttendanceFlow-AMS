<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected UserManagementService $userService;

    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $teacherProfile = Auth::user()->teacherProfile;
        $students = $this->userService->getTeacherStudents($teacherProfile->id, $request->search);

        return view('teacher.students.index', compact('students'));
    }
}
