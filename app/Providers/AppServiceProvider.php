<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Membagikan data pengaturan ke semua view
        if (Schema::hasTable('settings')) {
            $globalSettings = Setting::pluck('value', 'key')->all();
            View::share('appSettings', $globalSettings);
        }
    }
}
