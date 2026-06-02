<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcademicService;
use App\Services\ReportingService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected AcademicService $academicService;
    protected ReportingService $reportingService;

    public function __construct(AcademicService $academicService, ReportingService $reportingService)
    {
        $this->academicService = $academicService;
        $this->reportingService = $reportingService;
    }

    public function index(Request $request)
    {
        $students = $this->reportingService->getStudentsWithFilters(
            $request->search,
            $request->group_id,
            10 // Per page
        );

        $groups = $this->academicService->getGroups();

        // Stats
        $totalStudents = $this->reportingService->getTotalStudentCount();
        $atRiskCount = $this->reportingService->getAtRiskStudentsCount();

        return view('admin.students.index', compact('students', 'groups', 'totalStudents', 'atRiskCount'));
    }
}
