<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OfficerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');
        
        if (!$user || $user['type'] !== 'officer') {
            return redirect()->route('login')->with('error', 'Access denied. Officer privileges required.');
        }

        return $next($request);
    }
}