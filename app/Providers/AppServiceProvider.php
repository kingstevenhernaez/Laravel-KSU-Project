<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator; // 🟢 Added this import to be safe

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
        // 1. Fixes key length for older MySQL
        Schema::defaultStringLength(191);

        // 2. Fixes pagination styling (Use Bootstrap 5)
        Paginator::useBootstrapFive();

        // 3. Register Observers (if any)
        // \App\Models\Event::observe(\App\Observers\EventObserver::class);
    }
}