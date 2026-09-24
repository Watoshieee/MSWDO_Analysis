<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureLiliwMunicipality
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !method_exists($user, 'isLiliwResident') || !$user->isLiliwResident()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This service is available only to Liliw residents.',
                ], 403);
            }

            return redirect()->route('user.programs')
                ->with('error', 'This service is available only to residents of Liliw.');
        }

        return $next($request);
    }
}
