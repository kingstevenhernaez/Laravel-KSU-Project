<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEnrollmentApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the key from the request header
        $providedKey = $request->header('X-KSU-API-KEY');

        // 2. Get the real key from our .env file
        $validKey = env('ENROLLMENT_API_KEY');

        // 3. Compare them
        if ($providedKey !== $validKey) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied: Invalid API Key.'
            ], 401); // 401 means Unauthorized
        }

        // 4. If they match, let them pass!
        return $next($request);
    }
}