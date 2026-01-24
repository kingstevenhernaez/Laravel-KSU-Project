<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <--- 1. ADD THIS LINE

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
        // <--- 2. ADD THIS BLOCK --->
        // This grants the Admin (ID 1) permission to do EVERYTHING.
        // It forces all sidebar buttons to appear, even if permissions are missing.
        Gate::before(function ($user, $ability) {
            if ($user->id === 1 || $user->email === 'admin@ksu.edu.ph') {
                return true;
            }
        });
        // <--- END BLOCK --->
    }
}