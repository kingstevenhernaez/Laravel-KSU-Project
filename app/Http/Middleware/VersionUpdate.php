<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VersionUpdate
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * One-time solution:
         * The base system includes a forced "version-update" / installer redirect.
         * For KSU deployment we disable forced redirects to avoid redirect loops.
         *
         * If you later want a controlled update process, implement it as an ADMIN-only page,
         * not as a global middleware redirect.
         */
        return $next($request);
    }
}
