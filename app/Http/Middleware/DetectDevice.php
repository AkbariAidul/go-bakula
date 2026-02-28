<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agent = new \Jenssegers\Agent\Agent();
        
        // Share device info with Inertia
        \Inertia\Inertia::share([
            'device' => [
                'isMobile' => $agent->isMobile(),
                'isTablet' => $agent->isTablet(),
                'isDesktop' => $agent->isDesktop(),
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
            ]
        ]);
        
        return $next($request);
    }
}
