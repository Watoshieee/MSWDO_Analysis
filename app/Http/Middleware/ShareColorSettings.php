<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ShareColorSettings
{
    public function handle(Request $request, Closure $next)
    {
        // Get user's municipality if authenticated
        $municipality = Auth::check() ? Auth::user()->municipality : null;
        
        if ($municipality) {
            // Get colors specific to user's municipality
            $primaryColor = AdminSetting::getByMunicipality('primary_color', $municipality, '#2C3E8F');
            $secondaryColor = AdminSetting::getByMunicipality('secondary_color', $municipality, '#FDB913');
            $accentColor = AdminSetting::getByMunicipality('accent_color', $municipality, '#C41E24');
        } else {
            // Default colors for non-authenticated users
            $primaryColor = '#2C3E8F';
            $secondaryColor = '#FDB913';
            $accentColor = '#C41E24';
        }

        View::share('primaryColor', $primaryColor);
        View::share('secondaryColor', $secondaryColor);
        View::share('accentColor', $accentColor);

        return $next($request);
    }
}
