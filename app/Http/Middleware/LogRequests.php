<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;

        if ($response->status() >= 400) {
            \Log::warning('Failed Response', [
                'status' => $response->status(),
                'time_ms' => round($duration * 1000, 2),
                'url' => $request->fullUrl(),
            ]);
        }

        return $response;
    }
}
