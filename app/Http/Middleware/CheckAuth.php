<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckAuth
{
    public function handle(Request $request, Closure $next, $userType = null)
    {
        // Check if user is authenticated
        if (!session('authenticated')) {
            $this->clearSession();
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check session token validity (prevents direct URL access)
        if (!$this->isValidSession($request)) {
            $this->clearSession();
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Check user type authorization
        if ($userType && session('user_type') !== $userType) {
            $this->clearSession();
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Check if session should expire on page refresh
        if ($this->shouldExpireSession($request)) {
            $this->clearSession();
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Regenerate session token for next request
        $this->regenerateSessionToken();

        return $next($request);
    }

    /**
     * Check if the session is valid
     */
    private function isValidSession(Request $request): bool
    {
        $sessionToken = session('session_token');
        $lastActivity = session('last_activity');

        // Check if session token exists
        if (!$sessionToken) {
            return false;
        }

        // Check if session has expired (2 hours of inactivity for admins, 30 minutes for others)
        if ($lastActivity && session('user_type') === 'admin') {
            // 2 hours (7200 seconds) for admin users
            if ((time() - $lastActivity) > 7200) {
                return false;
            }
        } elseif ($lastActivity && (time() - $lastActivity) > 1800) {
            // 30 minutes for other users
            return false;
        }

        return true;
    }

    /**
     * Check if session should expire (on page refresh without proper navigation)
     */
    private function shouldExpireSession(Request $request): bool
    {
        // Never expire session for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Never expire session for form submissions
        if ($request->isMethod('post') || $request->isMethod('patch') || $request->isMethod('put') || $request->isMethod('delete')) {
            return false;
        }

        // Allow all GET requests (including page refreshes)
        if ($request->isMethod('get')) {
            return false;
        }

        // Get the referer and current URL
        $referer = $request->header('referer');
        $currentUrl = $request->url();

        // If no referer, allow access to dashboard routes
        if (!$referer) {
            $validDirectAccess = [
                '/dashboard',
                '/officer',
                '/director',
                '/vp',
                '/dean',
                '/head-office',
                '/psg-council'
            ];

            foreach ($validDirectAccess as $validPath) {
                if (str_contains($request->getPathInfo(), $validPath)) {
                    return false; // Allow direct access to app routes
                }
            }
        }

        // If referer is from the same domain, allow
        if ($referer) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            $currentHost = parse_url($currentUrl, PHP_URL_HOST);

            if ($refererHost === $currentHost) {
                return false; // Same domain, allow
            }
        }

        // Only expire session for truly suspicious access patterns
        return false; // Changed to be more permissive
    }

    /**
     * Generate a new session token
     */
    private function regenerateSessionToken(): void
    {
        session([
            'session_token' => Str::random(60),
            'last_activity' => time()
        ]);
    }

    /**
     * Clear all session data
     */
    private function clearSession(): void
    {
        session()->forget([
            'user',
            'user_type',
            'admin_role',
            'authenticated',
            'session_token',
            'last_activity'
        ]);
        session()->flush();
    }
}