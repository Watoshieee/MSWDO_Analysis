<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin routes (web middleware applied by default via web.php-style loading)
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            // SuperAdmin routes
            Route::middleware('web')
                ->group(base_path('routes/superadmin.php'));

            // Mobile API routes — versioned under /mobile-api/v1
            Route::prefix('mobile-api/v1')
                ->middleware('api')
                ->group(base_path('routes/mobile_api.php'));

            // Backward compatibility: keep /mobile-api prefix working during transition
            Route::prefix('mobile-api')
                ->middleware('api')
                ->group(base_path('routes/mobile_api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. No-cache headers on every response (back-button bypass fix)
        // 2. Inject session-guard.js into every authenticated HTML response
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
            \App\Http\Middleware\InjectSessionGuard::class,
        ]);

        // Named middleware aliases
        // - 'role'        → Hard 403 abort if wrong role (used on admin/superadmin routes)
        // - 'ensure_role' → Graceful redirect to correct dashboard (used on user routes)
        $middleware->alias([
            'role'        => \App\Http\Middleware\RoleMiddleware::class,
            'ensure_role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return consistent JSON errors for mobile API requests
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('mobile-api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('mobile-api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
    })->create();
