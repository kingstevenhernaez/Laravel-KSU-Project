<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // One-time solution:
        // This project originally ships with an installer wizard.
        // For KSU deployment we disable that wizard and mark as installed automatically.
        $installedFlag = storage_path('installed');

        if (!file_exists($installedFlag)) {
            try {
                @file_put_contents($installedFlag, 'installed:' . now());
            } catch (\Throwable $e) {
                // ignore write failures; do not redirect
            }

            // Optional: create storage link quietly (do not redirect on failure)
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return $next($request);
    }
}
