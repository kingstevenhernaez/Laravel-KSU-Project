<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure installer wizard is never required
        $installedFlag = storage_path('installed');
        if (!file_exists($installedFlag)) {
            try {
                @file_put_contents($installedFlag, 'installed:' . now());
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // If not logged in, send to login (no installer redirect)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Keep the original role enforcement
        if ((int) (auth()->user()->role ?? 0) === (int) USER_ROLE_SUPER_ADMIN) {
            return $next($request);
        }

        abort(403);
    }
}
