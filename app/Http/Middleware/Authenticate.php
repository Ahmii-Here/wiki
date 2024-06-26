<?php

namespace BookStack\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!user()->hasAppAccess()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

        }

        return $next($request);
    }
}
