<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSetting;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $primaryColor = AdminSetting::get('primary_color', '#2C3E8F');
        $secondaryColor = AdminSetting::get('secondary_color', '#FDB913');
        $accentColor = AdminSetting::get('accent_color', '#C41E24');
        
        return view('admin.settings', compact('primaryColor', 'secondaryColor', 'accentColor'));
    }

    public function get()
    {
        return response()->json([
            'primary_color' => AdminSetting::get('primary_color', '#2C3E8F', auth()->id()),
            'secondary_color' => AdminSetting::get('secondary_color', '#FDB913', auth()->id()),
            'accent_color' => AdminSetting::get('accent_color', '#C41E24', auth()->id())
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
        ]);

        AdminSetting::set('primary_color', $request->primary_color, auth()->id());
        AdminSetting::set('secondary_color', $request->secondary_color, auth()->id());
        AdminSetting::set('accent_color', $request->accent_color, auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'UI colors updated successfully!'
        ]);
    }

    public function reset()
    {
        AdminSetting::set('primary_color', '#2C3E8F', auth()->id());
        AdminSetting::set('secondary_color', '#FDB913', auth()->id());
        AdminSetting::set('accent_color', '#C41E24', auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Colors reset to default!'
        ]);
    }
}
