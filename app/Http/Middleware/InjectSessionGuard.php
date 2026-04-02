<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Injects the session-guard.js heartbeat script into every authenticated
 * HTML page response — so we don't need to modify every individual blade file.
 *
 * The script:
 *   • Pings /session/ping every 45 s while the tab is visible.
 *   • On tab hide/close, records the time in localStorage.
 *   • On next page load / tab restore, auto-logs out if > 2 min have passed.
 */
class InjectSessionGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only inject for authenticated users on HTML responses
        if (!Auth::check()) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        // Build version-busted script tag
        $jsPath  = public_path('js/session-guard.js');
        $version = file_exists($jsPath) ? filemtime($jsPath) : time();
        $tag     = "\n<script src=\"/js/session-guard.js?v={$version}\"></script>";

        $content = $response->getContent();
        if (str_contains($content, '</body>')) {
            $content = str_replace('</body>', $tag . "\n</body>", $content);
            $response->setContent($content);
        }

        return $response;
    }
}
