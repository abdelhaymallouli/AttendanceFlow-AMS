<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $api;

    public function __construct(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function dashboard($id = 1) // Default student ID for sprint 1 demo
    {
        $stats = $this->api->getStudentStats($id);
        $attendanceData = $this->api->getStudentAttendance($id);
        
        $notifications = [];
        if ($stats && isset($stats['user_id'])) {
            $notifications = $this->api->getUserNotifications($stats['user_id']);
        }
        
        return view('mobile.student.dashboard', compact('stats', 'attendanceData', 'notifications'));
    }
}
