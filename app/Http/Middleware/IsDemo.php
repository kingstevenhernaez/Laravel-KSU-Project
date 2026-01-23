<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDemo
{
    public function handle(Request $request, Closure $next)
    {
        // Disable demo restriction permanently for this deployment.
        return $next($request);
    }
}
