<?php

namespace App\Providers;

use App\Services\ApplicationService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\OtpService;
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
        //
    }
}
