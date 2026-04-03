<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    public function getFilieres()
    {
        return response()->json(Filiere::all());
    }

    public function getGroups()
    {
        return response()->json(Group::with('filiere')->get());
    }

    public function getModules()
    {
        return response()->json(Module::all());
    }

    public function getSessions()
    {
        return response()->json(Session::with(['group.studentProfiles.user', 'module', 'teacherProfile.user'])->get());
    }

    public function getTeacherSessions($id)
    {
        return response()->json(Session::where('teacher_profile_id', $id)
            ->with(['group.studentProfiles.user', 'module'])
            ->get());
    }

    public function getSession($id)
    {
        return response()->json(Session::with(['group.studentProfiles.user', 'module', 'teacherProfile.user'])
            ->findOrFail($id));
    }
}
