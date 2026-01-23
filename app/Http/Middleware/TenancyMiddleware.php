<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Database\Models\Domain;

class TenancyMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * NOTE: Domain lookup is executed on every request and can become expensive.
     * Cache the lookup per-host to keep admin navigation responsive.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        $domain = Cache::remember("tenancy.domain.by_host.{$host}", now()->addMinutes(10), function () use ($host) {
            return Domain::where('domain', $host)->first();
        });

        if (is_null($domain) && isAddonInstalled('ALUSAAS')) {
            abort(404);
        }

        return $next($request);
    }
}
