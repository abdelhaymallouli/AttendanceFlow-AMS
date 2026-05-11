<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Module;
use App\Models\Group;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    /**
     * Display the daily schedule of sessions.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
            ->whereDate('start_time', $date)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.sessions.index', compact('sessions', 'date'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $modules = Module::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $teacherProfiles = TeacherProfile::with('user')->get();

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

        Session::create([
            'module_id' => $request->module_id,
            'teacher_profile_id' => $request->teacher_id,
            'group_id' => $request->group_id,
            'type' => $request->type,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('admin.sessions.index', ['date' => $request->date])
            ->with('success', 'Session created successfully!');
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(Session $session)
    {
        $modules = Module::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $teacherProfiles = TeacherProfile::with('user')->get();

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

        $session->update([
            'module_id' => $request->module_id,
            'teacher_profile_id' => $request->teacher_id,
            'group_id' => $request->group_id,
            'type' => $request->type,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('admin.sessions.index', ['date' => $request->date])
            ->with('success', 'Session updated successfully!');
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(Session $session)
    {
        $date = Carbon::parse($session->start_time)->toDateString();
        $session->delete();

        return redirect()->route('admin.sessions.index', ['date' => $date])
            ->with('success', 'Session deleted successfully!');
    }
}
