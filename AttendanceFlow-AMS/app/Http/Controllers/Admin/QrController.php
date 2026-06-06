<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin-facing QR launcher.
 *
 * Lists ALL groups of the school for a given date (defaults to today)
 * with each session's start time, module, teacher, and present count,
 * so the admin can launch a QR workspace for ANY group — not just the
 * groups they teach.
 */
class QrController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->query('date', now()->toDateString());

        $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        $groupedByGroup = $sessions->groupBy(fn ($s) => $s->group?->id ?? 0);

        $groups = Group::with('filiere')
            ->orderBy('name')
            ->get()
            ->keyBy('id');

        $allGroups = $groups->map(function ($group) use ($groupedByGroup) {
            $sessionsForGroup = $groupedByGroup->get($group->id, collect());

            return [
                'id' => $group->id,
                'name' => $group->name,
                'filiere' => $group->filiere?->name,
                'sessions' => $sessionsForGroup->map(function (Session $session) {
                    return [
                        'id' => $session->id,
                        'module' => $session->module?->name,
                        'teacher' => $session->teacherProfile?->user?->name,
                        'start_time' => $session->start_time->format('H:i'),
                        'end_time' => $session->end_time->format('H:i'),
                        'type' => $session->type,
                        'is_past' => now()->greaterThan($session->end_time),
                        'is_active' => now()->between($session->start_time, $session->end_time),
                        'show_url' => route('admin.sessions.qr.show', $session->id),
                    ];
                })->values(),
            ];
        })->values();

        return view('admin.qr.index', [
            'date' => $date,
            'allGroups' => $allGroups,
            'totalSessions' => $sessions->count(),
        ]);
    }
}
