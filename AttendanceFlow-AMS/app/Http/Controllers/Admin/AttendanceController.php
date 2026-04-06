<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a list of sessions for which to mark attendance.
     */
    public function index()
    {
        $today = Carbon::today();
        
        // Fetch sessions with relationships
        $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Categorize sessions for the view
        $todaySessions = $sessions->filter(fn($s) => Carbon::parse($s->start_time)->isToday());
        $pastSessions = $sessions->filter(fn($s) => Carbon::parse($s->start_time)->isPast() && !Carbon::parse($s->start_time)->isToday());
        $upcomingSessions = $sessions->filter(fn($s) => Carbon::parse($s->start_time)->isFuture());

        return view('admin.attendance.index', compact('todaySessions', 'pastSessions', 'upcomingSessions'));
    }
}
