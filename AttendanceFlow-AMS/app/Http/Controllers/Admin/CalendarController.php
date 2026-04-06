<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.calendar', compact('sessions'));
    }
}
