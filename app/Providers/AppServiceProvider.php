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
        if (Schema::hasTable('settings')) {
            // Ambil semua settings dan jadikan default jika kosong
            $settings = Setting::pluck('value', 'key')->all();

            // Siapkan fallback default jika key belum ada di database
            $defaults = [
                'business_name' => 'Mahligai AutoCare',
                'business_address' => 'Jl. Jend. Sudirman No. 123, Pekanbaru',
                'business_phone' => '+62 812 3456 7890',
                'business_email' => 'info@mahligaiautocare.com',
                'business_hours' => 'Senin - Minggu: 08:00 - 20:00 WIB',
            ];

            // Gabungkan default dengan data dari DB (data DB akan menimpa default)
            $globalSettings = array_merge($defaults, $settings);

            View::share('appSettings', $globalSettings);
        }
    }
}
