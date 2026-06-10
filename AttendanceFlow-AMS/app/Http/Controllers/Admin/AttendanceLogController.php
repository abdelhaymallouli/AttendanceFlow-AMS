<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AttendanceRecord::with(['studentProfile.user', 'session.module', 'deviceFingerprint', 'qrToken'])
            ->latest('updated_at');

        // Filter by method
        if ($request->filled('method')) {
            $query->where('check_in_method', $request->method);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search query (user name or matricule)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('studentProfile.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('studentProfile', function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }
}
