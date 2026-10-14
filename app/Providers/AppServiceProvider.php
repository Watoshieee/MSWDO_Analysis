<?php

namespace App\Providers;

use App\Services\ApplicationService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\OtpService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OtpService::class);
        $this->app->singleton(AuthService::class);
        $this->app->singleton(ApplicationService::class);
        $this->app->singleton(DashboardService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            URL::forceScheme('https');
            $rootUrl = rtrim((string) config('app.url'), '/');
            if ($rootUrl !== '') {
                URL::forceRootUrl($rootUrl);
            }
        }

        // Generate logo path compatible with both root domain and subpath configurations.
        $basePath = '';
        if (! $this->app->runningInConsole()) {
            try {
                $basePath = rtrim((string) request()->getBasePath(), '/');
            } catch (\Throwable $e) {
                $basePath = '';
            }
        }
        $logoUrl = ($basePath !== '' ? $basePath : '') . '/images/logo_mswdo.jpg';
        View::share('mswdoLogoUrl', $logoUrl);
    }
}
