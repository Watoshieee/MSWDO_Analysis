<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds HTTP cache-control headers on every response.
 * This forces the browser to always re-request a page from the server
 * instead of serving a cached copy — fixing the back-button bypass after logout.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma',        'no-cache')
            ->header('Expires',       'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
