<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JustificationController extends Controller
{
    public function getPending()
    {
        return response()->json(Justification::where('status', 'pending')
            ->with(['studentProfile.user'])
            ->get());
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

        $data['status'] = 'pending';
        $data['submitted_at'] = now();

        $justification = Justification::create($data);

        return response()->json(['message' => 'Justification submitted successfully', 'data' => $justification]);
    }
}
