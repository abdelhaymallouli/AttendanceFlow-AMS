<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $location = CampusLocation::active() ?? CampusLocation::first();

        if (!$location) {
            $location = CampusLocation::create([
                'name' => 'Solicode Casablanca',
                'code' => 'CASA-SOLI',
                'latitude' => 33.5731,
                'longitude' => -7.5898,
                'radius_meters' => 50,
                'allowed_subnets' => ['192.168.10.0/24', '10.190.0.0/16'],
                'is_active' => true,
            ]);
        }

        return view('admin.settings', compact('location'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:1|max:10000',
            'allowed_subnets' => 'nullable|string',
        ]);

        $location = CampusLocation::active() ?? CampusLocation::first();

        // Convert subnet lines/comma list to array
        $subnetsStr = $request->input('allowed_subnets', '');
        $subnets = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $subnetsStr))));

        $location->update([
            'latitude' => (float) $request->latitude,
            'longitude' => (float) $request->longitude,
            'radius_meters' => (int) $request->radius_meters,
            'allowed_subnets' => array_values($subnets),
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Configurations de sécurité et GPS mises à jour avec succès.');
    }
}
