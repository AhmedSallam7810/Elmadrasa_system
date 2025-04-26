<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Services\Contracts\AttendanceContract',
            'App\Services\Services\AttendanceService'
        );
        $this->app->bind(
            'App\Repositories\Interfaces\AttendanceRepositoryInterface',
            'App\Repositories\Repositories\AttendanceRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
