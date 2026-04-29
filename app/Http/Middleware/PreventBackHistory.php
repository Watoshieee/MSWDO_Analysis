<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds HTTP cache-control headers on every response.
 * This forces the browser to always re-request a page from the server
 * instead of serving a cached copy — fixing the back-button bypass after logout.
 *
 * Note: BinaryFileResponse (used by response()->file()) does not support the
 * fluent ->header() method, so we fall back to the Symfony headers bag API.
 */
class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ];

        // BinaryFileResponse (response()->file() / response()->download()) does not
        // expose a fluent ->header() method — use the Symfony headers bag directly.
        if (method_exists($response, 'header')) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
        } else {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
