<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Graceful role gate — redirects to the correct dashboard if the user's role doesn't match.
 *
 * Usage: middleware('ensure_role:user')
 *
 * Use this for user-facing routes where a mis-routed admin should be
 * sent to their own dashboard instead of seeing a 403.
 * For admin/superadmin routes that should hard-block, use 'role' instead.
 *
 * @see \App\Http\Middleware\RoleMiddleware  Hard 403 alternative
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
