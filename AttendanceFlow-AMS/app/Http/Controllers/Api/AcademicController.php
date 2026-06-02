<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AcademicService;

class AcademicController extends Controller
{
    protected AcademicService $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function getFilieres()
    {
        return response()->json($this->academicService->getFilieres());
    }

    public function getGroups()
    {
        return response()->json($this->academicService->getGroups());
    }

    public function getModules()
    {
        return response()->json($this->academicService->getModules());
    }

    public function getSessions()
    {
        return response()->json($this->academicService->getSessions());
    }

    public function getTeacherSessions($id)
    {
        return response()->json($this->academicService->getTeacherSessions($id));
    }

    public function getSession($id)
    {
        return response()->json($this->academicService->getSession($id));
    }
}
