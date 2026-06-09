<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JustificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JustificationController extends Controller
{
    protected JustificationService $justificationService;

    public function __construct(JustificationService $justificationService)
    {
        $this->justificationService = $justificationService;
    }

    public function getPending()
    {
        return response()->json($this->justificationService->getPending());
    }

    public function getStudentJustifications($id)
    {
        return response()->json($this->justificationService->getStudentJustifications($id));
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_profile_id' => 'required|exists:student_profiles,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:medical,family,academic,other',
            'document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'student_profile_id', 'session_id', 'reason', 'start_date', 'end_date', 'type'
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('justifications', 'public');
            $data['document_name'] = basename($path);
        }

        try {
            $justification = $this->justificationService->submitJustification($request->student_profile_id, $data);
            return response()->json(['message' => 'Justification submitted successfully', 'data' => $justification]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['submission' => [$e->getMessage()]]], 422);
        }
    }
}
