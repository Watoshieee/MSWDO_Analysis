<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AdminSetting;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $userId = auth()->id();
                $view->with('adminPrimaryColor', AdminSetting::where('user_id', $userId)->where('setting_key', 'primary_color')->value('setting_value') ?? '#2C3E8F');
                $view->with('adminSecondaryColor', AdminSetting::where('user_id', $userId)->where('setting_key', 'secondary_color')->value('setting_value') ?? '#FDB913');
                $view->with('adminAccentColor', AdminSetting::where('user_id', $userId)->where('setting_key', 'accent_color')->value('setting_value') ?? '#C41E24');
            } else {
                $view->with('adminPrimaryColor', '#2C3E8F');
                $view->with('adminSecondaryColor', '#FDB913');
                $view->with('adminAccentColor', '#C41E24');
            }
        });
    }
}
