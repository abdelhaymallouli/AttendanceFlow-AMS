<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $api;

    public function __construct(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    /**
     * Display a listing of sessions.
     */
    public function index()
    {
        $sessions = $this->api->getSessions();
        
        return view('mobile.sessions.index', compact('sessions'));
    }

    /**
     * Display the specified session with attendance records.
     */
    public function show($id)
    {
        $session = $this->api->getSession($id);
        $attendanceData = $this->api->getSessionAttendance($id);
        
        return view('mobile.sessions.show', compact('session', 'attendanceData'));
    }

    /**
     * Show flash attendance form for quick marking.
     */
    public function flash($id)
    {
        $session = $this->api->getSession($id);
        
        return view('mobile.attendance.flash', compact('session'));
    }
}