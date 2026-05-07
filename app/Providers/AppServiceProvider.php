<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('mailtrap-limit', function ($job) {
            // Cho phép gửi tối đa 5 email mỗi phút (phù hợp với gói free Mailtrap)
            return Limit::perMinute(5)->by('mailtrap');
        });
    }
}
