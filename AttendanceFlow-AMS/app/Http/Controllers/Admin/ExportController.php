<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcademicService;
use App\Services\ReportingService;
use App\Services\SchedulingService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected AcademicService $academicService;
    protected ReportingService $reportingService;
    protected SchedulingService $schedulingService;

    public function __construct(
        AcademicService $academicService,
        ReportingService $reportingService,
        SchedulingService $schedulingService
    ) {
        $this->academicService = $academicService;
        $this->reportingService = $reportingService;
        $this->schedulingService = $schedulingService;
    }

    public function exportStudents()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for proper Excel encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fwrite($handle, implode("\t", ['ID', 'Matricule', 'Nom', 'Email', 'Groupe']) . "\r\n");

            $students = $this->academicService->getStudentsForExport();

            foreach ($students as $student) {
                fwrite($handle, implode("\t", [
                    $student->id,
                    $student->matricule,
                    $student->user->name ?? 'N/A',
                    $student->user->email ?? 'N/A',
                    $student->group->name ?? 'N/A',
                ]) . "\r\n");
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="students_list.xls"',
        ]);

        return $response;
    }

    public function exportAttendance()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for proper Excel encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fwrite($handle, implode("\t", ['Date', 'Matricule', 'Étudiant', 'Groupe', 'Séance / Module', 'Statut']) . "\r\n");

            $records = $this->reportingService->getAttendanceForExport();

            foreach ($records as $record) {
                fwrite($handle, implode("\t", [
                    $record->date,
                    $record->studentProfile->matricule ?? 'N/A',
                    $record->studentProfile->user->name ?? 'N/A',
                    $record->studentProfile->group->name ?? 'N/A',
                    $record->session->module->name ?? 'N/A',
                    ucfirst($record->status),
                ]) . "\r\n");
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="attendance_history.xls"',
        ]);

        return $response;
    }

    public function exportSessions()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for proper Excel encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fwrite($handle, implode("\t", ['ID', 'Date', 'Heure Début', 'Heure Fin', 'Durée (h)', 'Module', 'Groupe', 'Formateur', 'Type']) . "\r\n");

            $sessions = $this->schedulingService->getSessionsForExport();

            foreach ($sessions as $session) {
                fwrite($handle, implode("\t", [
                    $session->id,
                    $session->start_time->format('Y-m-d'),
                    $session->start_time->format('H:i'),
                    $session->end_time->format('H:i'),
                    $session->duration_hours,
                    $session->module->name ?? 'N/A',
                    $session->group->name ?? 'N/A',
                    $session->teacherProfile->user->name ?? 'N/A',
                    strtoupper($session->type),
                ]) . "\r\n");
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sessions_schedule.xls"',
        ]);

        return $response;
    }
}
