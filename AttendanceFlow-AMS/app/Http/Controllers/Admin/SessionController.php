<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcademicService;
use App\Services\SchedulingService;
use App\Models\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    protected AcademicService $academicService;
    protected SchedulingService $schedulingService;

    public function __construct(AcademicService $academicService, SchedulingService $schedulingService)
    {
        $this->academicService = $academicService;
        $this->schedulingService = $schedulingService;
    }

    /**
     * Display the daily schedule of sessions.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $sessions = $this->schedulingService->getSessionsForDate($date);

        return view('admin.sessions.index', compact('sessions', 'date'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $modules = $this->academicService->getAllModules();
        $groups = $this->academicService->getAllGroupsWithFiliere();
        $teacherProfiles = $this->academicService->getAllTeacherProfiles(); // Need to add this to AcademicService

        return view('admin.sessions.create', compact('modules', 'groups', 'teacherProfiles'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'teacher_id' => 'required|exists:teacher_profiles,id',
            'group_id' => 'required|exists:groups,id',
            'type' => 'required|in:lecture,td,tp',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);

        try {
            $this->schedulingService->scheduleSession([
                'module_id' => $request->module_id,
                'teacher_profile_id' => $request->teacher_id,
                'group_id' => $request->group_id,
                'type' => $request->type,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return redirect()->route('admin.sessions.index', ['date' => $request->date])
                ->with('success', 'Session created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(Session $session)
    {
        $modules = $this->academicService->getAllModules();
        $groups = $this->academicService->getAllGroupsWithFiliere();
        $teacherProfiles = $this->academicService->getAllTeacherProfiles(); // Need to add this to AcademicService

        return view('admin.sessions.edit', compact('session', 'modules', 'groups', 'teacherProfiles'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, Session $session)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'teacher_id' => 'required|exists:teacher_profiles,id',
            'group_id' => 'required|exists:groups,id',
            'type' => 'required|in:lecture,td,tp',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);

        try {
            $this->schedulingService->updateSession($session, [
                'module_id' => $request->module_id,
                'teacher_profile_id' => $request->teacher_id,
                'group_id' => $request->group_id,
                'type' => $request->type,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return redirect()->route('admin.sessions.index', ['date' => $request->date])
                ->with('success', 'Session updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(Session $session)
    {
        $date = Carbon::parse($session->start_time)->toDateString();
        $this->schedulingService->deleteSession($session);

        return redirect()->route('admin.sessions.index', ['date' => $date])
            ->with('success', 'Session deleted successfully!');
    }
}
