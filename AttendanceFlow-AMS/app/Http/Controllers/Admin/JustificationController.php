<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use Illuminate\Http\Request;

class JustificationController extends Controller
{
    public function index()
    {
        $justifications = Justification::with('studentProfile.user')
            ->orderBy('status', 'asc') // Pending first? status logic pending...
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('admin.justifications', compact('justifications'));
    }

    public function update(Request $request, Justification $justification)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $justification->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Justification ' . $request->status . '.');
    }
}
