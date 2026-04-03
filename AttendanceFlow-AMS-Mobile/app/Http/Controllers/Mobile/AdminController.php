<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $api;

    public function __construct(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function dashboard()
    {
        $stats = $this->api->getAdminStats();
        $pendingJustifications = $this->api->getPendingJustifications();
        
        return view('mobile.admin.dashboard', compact('stats', 'pendingJustifications'));
    }
}
