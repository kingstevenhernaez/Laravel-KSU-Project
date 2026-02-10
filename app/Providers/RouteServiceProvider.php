<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // Where to redirect after login
    public const HOME = '/home';
    public const ADMIN = '/admin/dashboard';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // 1. API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // 2. PUBLIC WEB ROUTES (Home, Login, Register)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // 3. ADMIN ROUTES (ENABLED NOW)
            Route::middleware(['web', 'auth']) // Added 'auth' so only logged-in users can see it
                 ->prefix('admin')
                 ->name('admin.')
                 ->group(base_path('routes/admin.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}