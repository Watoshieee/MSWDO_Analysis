<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Hard role gate — aborts with 403 if the user doesn't match.
 *
 * Usage: middleware('role:admin') or middleware('role:super_admin')
 *
 * Use this for admin/superadmin routes where an unauthorized user should
 * be blocked outright. For user-facing routes that should gracefully
 * redirect to the correct dashboard, use 'ensure_role' instead.
 *
 * @see \App\Http\Middleware\EnsureUserRole  Graceful redirect alternative
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access. You do not have the required role.');
    }
}