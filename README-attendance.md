# 📅 Complete Codebase Reference: Admin Attendance Module

This reference document contains the full, unmodified source code for all views, controllers, services, and JavaScript components that power the admin attendance session selection, filters, and marking features in the AttendanceFlow-AMS.

---

## 🗺️ System Overview & Interaction Map

```
                  [ Web Browser (User) ]
                            │
               (Routes: routes/web.php)
                            │
       [ App\Http\Controllers\Admin\AttendanceController ]
         ╱                  │                  ╲
        ╱                   │                   ╲
  (Loads views)      (Invokes Service)     (Fetches/Saves data)
      │                     │                     │
[ index / show ]   [ AttendanceService ]    [ Session, Group, ]
[  blade views ]            │               [ Student, Record ]
      │             (Justifications &)            │
 (Includes components) (Notifications)            │
      │                     │                     │
[ Alpine.js Assets ] ◄──────┴─────────────────────┘
```

---

## 🗂️ Table of Contents
1. **Controller**: `app/Http/Controllers/Admin/AttendanceController.php`
2. **Service**: `app/Services/AttendanceService.php`
3. **Index View (Session Filter)**: `resources/views/admin/attendance/index.blade.php`
4. **Marking View (Session Form)**: `resources/views/admin/attendance/show.blade.php`
5. **AlpineJS Index Filter JS**: `resources/js/attendance.js`
6. **AlpineJS Marking Core JS**: `resources/js/attendance-mark.js`

---

## 1. Controller
### 📌 File: `app/Http/Controllers/Admin/AttendanceController.php`
```php
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
    public function index(Request $request)
    {
        $date = $request->input('date');
        $sessionType = $request->input('session_type');
        
        // Defensive redirect to avoid 'undefined' string or invalid formats in browser URL
        if (!$date || $date === 'undefined' || !strtotime($date)) {
            $correctDate = \Carbon\Carbon::today()->toDateString();
            return redirect()->route('admin.attendance.index', ['date' => $correctDate, 'session_type' => $sessionType]);
        }

        $query = Session::with(['module', 'group', 'teacherProfile.user']);

        if ($date) {
            $query->whereDate('start_time', $date);
        }
        
        if ($sessionType) {
            $query->where('type', $sessionType);
        }

        $sessions = $query->orderBy('start_time')->get();

        // Prepare session data for Alpine.js component
        $allSessionsData = $sessions->map(function($session) {
            $start = \Carbon\Carbon::parse($session->start_time);
            $end = \Carbon\Carbon::parse($session->end_time);
            return [
                'id' => $session->id,
                'start_time' => $start->format('Y-m-d H:i:s'),
                'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'duration' => $end->diffInHours($start),
                'module' => $session->module->name,
                'group' => $session->group->name,
                'teacher' => $session->teacherProfile->user->name,
                'url' => route('admin.attendance.show', $session->id)
            ];
        })->values();

        return view('admin.attendance.index', compact('sessions', 'date', 'sessionType', 'allSessionsData'));
    }

    /**
     * Show the attendance marking form for a session.
     */
    public function show(Session $session)
    {
        // Load the group's students
        $students = $session->group->studentProfiles()->with('user')->get();
        
        // Load existing records for this session
        $existingRecords = \App\Models\AttendanceRecord::where('session_id', $session->id)->get()->pluck('status', 'student_profile_id');

        return view('admin.attendance.show', compact('session', 'students', 'existingRecords'));
    }

    /**
     * Store the attendance records for the session.
     */
    public function store(Request $request, Session $session)
    {
        $request->validate([
            'attendance' => 'nullable|array',
            'attendance.*' => 'in:present,absent,late',
        ]);

        $attendanceData = $request->attendance ?? [];
        $submittedStudentIds = array_keys($attendanceData);
        $sessionDate = Carbon::parse($session->start_time)->toDateString();

        // Delete records for students who are no longer marked (cleared/unmarked)
        \App\Models\AttendanceRecord::where('session_id', $session->id)
            ->whereNotIn('student_profile_id', $submittedStudentIds)
            ->delete();

        $attendanceService = app(\App\Services\AttendanceService::class);

        foreach ($attendanceData as $studentId => $status) {
            $attendanceService->markAttendance((int) $studentId, $session->id, $status, $sessionDate);
        }

        return redirect()
            ->route('admin.attendance.index', ['date' => $sessionDate])
            ->with('success', 'Attendance saved successfully!');
    }
}
```

---

## 2. Service
### 📌 File: `app/Services/AttendanceService.php`
```php
<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Illuminate\Support\Collection;

/**
 * AttendanceService
 * 
 * Records and analyzes student presence.
 */
class AttendanceService extends BaseService
{
    // Service name is now automatically handled by BaseService

    /**
     * Mark attendance for a single student.
     */
    public function markAttendance(int $studentProfileId, int $sessionId, string $status, string $date): AttendanceRecord
    {
        $this->logInfo("Marking attendance for student {$studentProfileId} in session {$sessionId}: {$status}");
        
        $justification = null;
        if ($status === 'absent' || $status === 'absent_unexcused' || $status === 'absent_excused') {
            $justification = \App\Models\Justification::where([
                'student_profile_id' => $studentProfileId,
                'session_id' => $sessionId,
            ])->where('status', 'approved')->first();

            if ($justification) {
                $status = 'absent_excused';
            } else {
                $status = 'absent_unexcused';
            }
        }

        $record = AttendanceRecord::updateOrCreate(
            ['student_profile_id' => $studentProfileId, 'session_id' => $sessionId],
            [
                'status' => $status, 
                'date' => $date,
                'justification_id' => $justification ? $justification->id : null
            ]
        );

        if (in_array($status, ['absent_unexcused', 'absent_excused', 'late'])) {
            $student = \App\Models\StudentProfile::find($studentProfileId);
            $session = \App\Models\Session::with(['module', 'teacherProfile.user'])->find($sessionId);
            
            if ($student && $student->user_id) {
                $moduleName = $session->module->name ?? 'Séance';
                $statusLabel = $status === 'absent_excused' ? 'absent (justifié)' : ($status === 'absent_unexcused' ? 'absent (non justifié)' : 'en retard');
                \App\Models\Notification::create([
                    'user_id' => $student->user_id,
                    'title' => $status === 'late' ? 'Retard signalé' : 'Nouvelle absence signalée',
                    'message' => 'Vous avez été marqué ' . $statusLabel . ' le ' . \Carbon\Carbon::parse($date)->format('d/m/Y') . ' pour la séance de : ' . $moduleName . '.',
                    'type' => $status === 'absent_unexcused' ? 'danger' : ($status === 'absent_excused' ? 'success' : 'info'),
                ]);
            }

            // Notify teacher
            if ($session && $session->teacherProfile && $session->teacherProfile->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $session->teacherProfile->user_id,
                    'title' => 'Absence Étudiant',
                    'message' => 'L\'étudiant ' . ($student->user->name ?? 'inconnu') . ' a été marqué ' . ($status === 'absent_excused' ? 'absent (justifié)' : 'absent (non justifié)') . ' pour la séance de : ' . ($session->module->name ?? 'Séance') . '.',
                    'type' => 'danger',
                ]);
            }
        }

        return $record;
    }

    /**
     * Bulk mark attendance for an entire session.
     * Expected array format: [['student_profile_id' => 1, 'status' => 'present', 'date' => '2026-03-12'], ...]
     */
    public function bulkMarkAttendance(int $sessionId, array $attendanceData): void
    {
        $this->logInfo("Bulk marking attendance for session {$sessionId}");
        
        foreach ($attendanceData as $data) {
            $this->markAttendance($data['student_profile_id'], $sessionId, $data['status'], $data['date']);
        }
    }

    /**
     * Get all attendance records for a given session.
     */
    public function getSessionAttendance(int $sessionId): Collection
    {
        return AttendanceRecord::with('studentProfile.user')
            ->where('session_id', $sessionId)
            ->get();
    }

    /**
     * Get all attendance records for a student.
     */
    public function getStudentAttendance(int $studentProfileId): Collection
    {
        return AttendanceRecord::where('student_profile_id', $studentProfileId)->get();
    }
}
```

---

## 3. Index View (Session Filter)
### 📌 File: `resources/views/admin/attendance/index.blade.php`
```html
@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Session Selection')

@section('header_actions')
<a href="{{ route('admin.export.attendance') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
    <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export Absences (Excel)
</a>
@endsection

@section('content')
<div class="space-y-6" x-data="attendanceApp(@json($allSessionsData), '{{ $date }}')">
    
    <!-- Step 1: Date + Session Selector -->
    <x-ui.section-card padding="p-4" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <!-- Step label -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold shadow-sm shadow-blue-500/30">1</span>
                    Select Date &amp; Session
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-8">Sessions are filtered by the selected date</p>
            </div>

            <!-- Date Input -->
            <x-date-filter 
                label="Date:"
                name="selectedDate"
                value="{{ $date }}"
                onChange="onDateChange($event.target.value)"
            />
            
            <!-- Session Type Filter -->
            <x-session-type-filter 
                name="session_type"
                value="{{ request('session_type') }}"
                onChange="onDateChange($event.target.value)"
            />
        </div>

        <!-- Session Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="session in availableSessions" :key="session.id">
                <a :href="session.url" class="session-tab flex items-start p-4 border rounded-xl transition-all bg-white text-left hover:border-blue-400 hover:shadow-md border-gray-200 group">
                    
                    <!-- Type icon -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 mt-0.5 bg-blue-50 group-hover:bg-blue-600 transition-colors">
                        <i data-lucide="clock" class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>

                    <!-- Session info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-800">
                            <span x-text="session.time"></span>
                            <span class="font-normal text-xs ml-1 text-gray-400" x-text="'(' + session.duration + 'h)'"></span>
                        </p>
                        
                        <p class="text-xs text-gray-500 truncate mt-1">
                            <span class="font-medium text-gray-700" x-text="session.module"></span>
                            <span class="mx-1 text-gray-300">·</span>
                            <span x-text="'Group ' + session.group"></span>
                        </p>
                        
                        <!-- Teacher -->
                        <p class="text-xs mt-1.5 text-gray-500 flex items-center">
                            <i data-lucide="user" class="w-3 h-3 mr-1 opacity-70"></i>
                            <span x-text="session.teacher"></span>
                        </p>
                    </div>
                </a>
            </template>
        </div>

        <!-- No sessions state -->
        <div x-show="availableSessions.length === 0">
            <x-ui.empty-state 
                icon="calendar-x"
                title="No sessions scheduled for this date"
                class="border-dashed"
            />
        </div>
    </x-ui.section-card>
</div>

@push('scripts')
@php
    $allSessionsData = $sessions->map(function($session) {
        $start = \Carbon\Carbon::parse($session->start_time);
        $end = \Carbon\Carbon::parse($session->end_time);
        return [
            'id' => $session->id,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            'duration' => $end->diffInHours($start),
            'module' => $session->module->name,
            'group' => $session->group->name,
            'teacher' => $session->teacherProfile->user->name,
            'url' => route('admin.attendance.show', $session->id)
        ];
    })->values();
@endphp

<script>
    const initialSessions = @json($allSessionsData);
</script>
@endpush
@endsection
```

---

## 4. Marking View (Session Form)
### 📌 File: `resources/views/admin/attendance/show.blade.php`
```html
@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Mark Attendance')

@section('content')
<div class="space-y-6" x-data="attendanceMark({{ $students->count() }})" x-init="updateStats()">

    <!-- Session Context Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-blue-900">
                    {{ $session->module->name }}
                    <span class="opacity-50">·</span> Group {{ $session->group->name }}
                </p>
                <p class="text-xs text-blue-700 mt-0.5">
                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                    <span class="opacity-50">·</span> {{ $students->count() }} students
                </p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="text-xs text-blue-600 hover:text-blue-800 underline font-medium self-start sm:self-center bg-white px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
            Change session
        </a>
    </div>

    <!-- Controls Bar -->
    <x-ui.section-card padding="p-4" class="mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input x-model="searchQuery" @input="filterRows()" type="text"
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 hover:bg-white transition-colors"
                        placeholder="Name or ID...">
                </div>
            </div>

            <!-- Bulk Actions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Actions</label>
                <div class="flex space-x-2">
                    <button @click="markAllPresent()" type="button"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center shadow-sm">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i>
                        All Present
                    </button>
                    <button @click="clearAll()" type="button"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center border border-gray-200">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1.5"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary + Save Bar -->
        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Present: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.present"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Absent: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.absent"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Late: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.late"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-gray-300 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Unmarked: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.unmarked"></strong></span>
                </div>
            </div>
            <button onclick="document.getElementById('attendanceForm').submit()"
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all flex items-center justify-center shadow-md hover:shadow-lg active:scale-95">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                Save Attendance
            </button>
        </div>
    </x-ui.section-card>

    <!-- Student List -->
    <form id="attendanceForm" action="{{ route('admin.attendance.store', $session) }}" method="POST">
        @csrf
        <x-ui.section-card :overflow="true" padding="none">
            
            <!-- Table Header (Desktop) -->
            <div class="hidden md:grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs text-gray-500 uppercase tracking-wider">
                <div class="col-span-1">#</div>
                <div class="col-span-2">Student ID</div>
                <div class="col-span-4">Name</div>
                <div class="col-span-5 text-center">Status</div>
            </div>

            <!-- Mobile Header -->
            <div class="md:hidden p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs text-gray-500 uppercase tracking-wider">
                <div class="flex justify-between">
                    <span>Student</span>
                    <span>Status</span>
                </div>
            </div>

            <!-- Student Rows -->
            <div class="divide-y divide-gray-100">
                @foreach($students as $student)
                    @php 
                        $currentStatus = $existingRecords->get($student->id, null);
                    @endphp
                    <x-student-attendance-row :student="$student" :currentStatus="$currentStatus" :loop="$loop" />
                @endforeach
            </div>
            
            @if($students->isEmpty())
                <x-ui.empty-state 
                    icon="users" 
                    title="No students found in this group."
                    class="border-0 shadow-none"
                />
            @endif
        </x-ui.section-card>
    </form>

</div>
@endsection
```

---

## 5. AlpineJS Index Filter JS
### 📌 File: `resources/js/attendance.js`
```javascript
/**
 * Attendance Session Selector (Admin)
 * =====================================
 * Alpine.js data component for the admin attendance index page.
 * Filters sessions by selected date without any page reload.
 *
 * Usage:
 *   <div x-data="attendanceApp(initialSessions)">
 *
 * Expects: initialSessions = array of session objects with { start_time, url, ... }
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceApp', (initialSessions = [], initialDate = '') => ({
        selectedDate: initialDate || new Date().toISOString().split('T')[0],
        availableSessions: initialSessions,

        onDateChange() {
            window.location.href = `?date=${this.selectedDate}`;
        }
    }));
});
```

---

## 6. AlpineJS Marking Core JS
### 📌 File: `resources/js/attendance-mark.js`
```javascript
/**
 * Attendance Mark App (Teacher / Admin)
 * ==============================
 * Alpine.js data component for the attendance marking page.
 * Handles student search, bulk actions, and real-time statistics.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceMark', (studentsCount = 0) => ({
        searchQuery: '',
        stats: { present: 0, absent: 0, late: 0, unmarked: studentsCount },
        
        init() {
            this.updateStats();
            setTimeout(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            }, 50);
        },
        
        updateStats() {
            const statuses = Array.from(document.querySelectorAll('input[type=radio]:checked')).map(r => r.value);
            this.stats.present = statuses.filter(s => s === 'present').length;
            this.stats.absent = statuses.filter(s => s === 'absent').length;
            this.stats.late = statuses.filter(s => s === 'late').length;
            this.stats.unmarked = this.stats.unmarked - statuses.length;
        },
        
        markAllPresent() {
            document.querySelectorAll('input[type=radio][value=present]').forEach(el => el.checked = true);
            this.updateStats();
        },
        
        clearAll() {
            document.querySelectorAll('input[type=radio]').forEach(el => el.checked = false);
            this.updateStats();
        },
        
        filterRows() {
            const query = this.searchQuery.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }
    }));
});
```