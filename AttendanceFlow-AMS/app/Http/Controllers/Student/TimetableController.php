<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function __construct(
        private readonly TimetableService $timetable,
    ) {
    }

    /**
     * GET /student/timetable
     * Weekly timetable for the logged-in student, based on their group(s).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->studentProfile;

        abort_if(! $profile, 403);

        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        $grid = $profile->group_id
            ? $this->timetable->weekGridFor($weekStart->toDateString(), 'group', $profile->group_id)
            : [];

        // Only published sessions
        foreach ($grid as $day => $sessions) {
            $grid[$day] = $sessions->where('is_published', true)->values();
        }

        return view('student.timetable.index', [
            'grid'      => $grid,
            'weekStart' => $weekStart,
            'weekEnd'   => (clone $weekStart)->endOfWeek(),
            'group'     => $profile->group,
        ]);
    }
}
