<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\SchedulingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessionController extends Controller
{
    protected SchedulingService $schedulingService;

    public function __construct(SchedulingService $schedulingService)
    {
        $this->schedulingService = $schedulingService;
    }

    public function create()
    {
        $teacherProfile = Auth::user()->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }

        $modules = $teacherProfile->modules()->orderBy('name')->get();
        $groups = $teacherProfile->groups()->orderBy('name')->get();

        return view('teacher.sessions.create', compact('modules', 'groups'));
    }

    public function store(Request $request)
    {
        $teacherProfile = Auth::user()->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }

        $request->validate([
            'module_id' => [
                'required',
                'exists:modules,id',
            ],
            'group_id' => [
                'required',
                'exists:groups,id',
            ],
            'type' => 'required|in:lecture,td,tp',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $assigned = DB::table('module_teacher_group')
            ->where('teacher_profile_id', $teacherProfile->id)
            ->where('module_id', $request->module_id)
            ->where('group_id', $request->group_id)
            ->exists();

        if (!$assigned) {
            return back()->withErrors(['error' => 'This module and group combination is not assigned to you.'])->withInput();
        }

        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);
        $durationHours = round($startTime->diffInMinutes($endTime) / 60, 1);

        try {
            $session = $this->schedulingService->scheduleSession([
                'module_id' => $request->module_id,
                'teacher_profile_id' => $teacherProfile->id,
                'group_id' => $request->group_id,
                'type' => $request->type,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $durationHours,
            ]);

            return redirect()->route('teacher.sessions.attendance.show', $session)
                ->with('success', 'Session created! You can now mark attendance.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
