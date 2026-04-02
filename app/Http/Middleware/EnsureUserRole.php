<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards route groups by role.
 * Usage in routes: middleware(['auth', 'ensure_role:user'])
 *
 * If the authenticated user's role doesn't match, they are redirected
 * to their own correct dashboard instead of seeing a 403.
 */
class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$allowedRoles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role ?? '';

        foreach ($allowedRoles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Wrong role — send them to their own dashboard
        return match ($userRole) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'user'        => redirect()->route('user.dashboard'),
            default       => redirect()->route('login'),
        };
    }
}
