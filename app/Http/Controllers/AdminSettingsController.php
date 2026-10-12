<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\MunicipalitySettings;
use App\Models\MunicipalityProgram;
use App\Models\MunicipalityVision;
use App\Models\Municipality;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $primaryColor = AdminSetting::getByMunicipality('primary_color', $user->municipality, AdminSetting::get('primary_color', '#1e2a78'));
        $secondaryColor = AdminSetting::getByMunicipality('secondary_color', $user->municipality, AdminSetting::get('secondary_color', '#f5b82e'));
        $accentColor = AdminSetting::getByMunicipality('accent_color', $user->municipality, AdminSetting::get('accent_color', '#c41e24'));
        
        $municipality = Municipality::where('name', $user->municipality)->first();
        $muniSettings = MunicipalitySettings::where('municipality_name', $user->municipality)->first();
        if (!$muniSettings) {
            $muniSettings = MunicipalitySettings::create([
                'municipality_name' => $user->municipality,
                'display_name' => 'Municipality of ' . $user->municipality,
                'is_active' => true
            ]);
        }

        $muniPrograms = MunicipalityProgram::where('municipality_name', $user->municipality)
            ->orderBy('display_order')
            ->get();

        $visionRow = MunicipalityVision::where('municipality_name', $user->municipality)->first();
        $visionData = [
            'vision' => $visionRow?->vision ?? '',
            'mission' => $visionRow?->mission ?? '',
            'goals' => $visionRow?->goals ?? '',
            'strategic_goals' => $visionRow?->strategic_goals ?? [],
        ];

        return view('admin.settings', compact(
            'primaryColor',
            'secondaryColor',
            'accentColor',
            'municipality',
            'muniSettings',
            'muniPrograms',
            'visionData'
        ));
    }

    public function get()
    {
        $user = auth()->user();
        return response()->json([
            'primary_color' => AdminSetting::getByMunicipality('primary_color', $user?->municipality, AdminSetting::get('primary_color', '#1e2a78')),
            'secondary_color' => AdminSetting::getByMunicipality('secondary_color', $user?->municipality, AdminSetting::get('secondary_color', '#f5b82e')),
            'accent_color' => AdminSetting::getByMunicipality('accent_color', $user?->municipality, AdminSetting::get('accent_color', '#c41e24'))
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
        ]);

        $user = auth()->user();
        $adminIds = \App\Models\User::where('role', 'admin')->where('municipality', $user->municipality)->pluck('id');
        if ($adminIds->isEmpty()) {
            $adminIds = collect([auth()->id()]);
        }
        foreach ($adminIds as $aid) {
            AdminSetting::set('primary_color', $request->primary_color, $aid);
            AdminSetting::set('secondary_color', $request->secondary_color, $aid);
            AdminSetting::set('accent_color', $request->accent_color, $aid);
        }

        return response()->json([
            'success' => true,
            'message' => 'UI colors updated successfully!'
        ]);
    }

    public function updateMunicipalitySettings(Request $request)
    {
        $request->validate([
            'display_name'   => 'nullable|string|max:200',
            'contact_email'  => 'nullable|email|max:200',
            'contact_phone'  => 'nullable|string|max:50',
            'office_address' => 'nullable|string|max:1000',
            'office_hours'   => 'nullable|string|max:200',
        ]);

        $municipality = auth()->user()->municipality;
        MunicipalitySettings::updateOrCreate(
            ['municipality_name' => $municipality],
            array_merge(
                $request->only(['display_name', 'contact_email', 'contact_phone', 'office_address', 'office_hours']),
                ['is_active' => true]
            )
        );

        return response()->json(['success' => true, 'message' => 'Municipality settings updated successfully!']);
    }

    public function reset()
    {
        $user = auth()->user();
        $adminIds = \App\Models\User::where('role', 'admin')->where('municipality', $user->municipality)->pluck('id');
        if ($adminIds->isEmpty()) {
            $adminIds = collect([auth()->id()]);
        }
        foreach ($adminIds as $aid) {
            AdminSetting::set('primary_color', '#1e2a78', $aid);
            AdminSetting::set('secondary_color', '#f5b82e', $aid);
            AdminSetting::set('accent_color', '#c41e24', $aid);
        }

        return response()->json([
            'success' => true,
            'message' => 'Colors reset to default!'
        ]);
    }

    public function updateProgram(Request $request, $id)
    {
        $request->validate([
            'program_label' => 'required|string|max:200',
            'description'   => 'nullable|string|max:1000',
            'display_order' => 'required|integer|min:0|max:999',
        ]);

        $program = MunicipalityProgram::where('id', $id)
            ->where('municipality_name', auth()->user()->municipality)
            ->firstOrFail();

        $program->update($request->only(['program_label', 'description', 'display_order']));

        return response()->json(['success' => true, 'message' => 'Program updated successfully!']);
    }

    public function toggleProgram($id)
    {
        $program = MunicipalityProgram::where('id', $id)
            ->where('municipality_name', auth()->user()->municipality)
            ->firstOrFail();

        $program->update(['is_active' => !$program->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $program->is_active,
            'message'   => $program->program_label . ' is now ' . ($program->is_active ? 'active' : 'inactive') . '.'
        ]);
    }
}