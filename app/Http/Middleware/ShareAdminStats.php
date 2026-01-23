<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Models\Alumni;

class ShareAdminStats
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $stats = Cache::remember('admin.sidebar.stats', 300, function () {
                return [
                    'alumni_count' => Alumni::count(),
                ];
            });

            view()->share('adminStats', $stats);
        }

        return $next($request);
    }
}
