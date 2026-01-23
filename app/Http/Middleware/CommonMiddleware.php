<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use App\Models\Department;
use App\Models\PassingYear;

class CommonMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only run heavy shared queries for admin routes
        if ($request->is('admin/*') || $request->is('dashboard')) {

            $shared = Cache::remember('admin.shared.data', 600, function () {
                return [
                    'setting' => Setting::first(),
                    'departments' => Department::orderBy('name')->get(),
                    'passingYears' => PassingYear::orderByDesc('year')->get(),
                ];
            });

            view()->share('setting', $shared['setting']);
            view()->share('departments', $shared['departments']);
            view()->share('passingYears', $shared['passingYears']);
        }

        return $next($request);
    }
}
