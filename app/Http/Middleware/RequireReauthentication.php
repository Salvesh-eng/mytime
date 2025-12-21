<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RequireReauthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Check if user has recently authenticated (within last 15 minutes)
        $lastAuthTime = Session::get('last_auth_time');
        $currentTime = now()->timestamp;
        $reauthInterval = 15 * 60; // 15 minutes in seconds

        if (!$lastAuthTime || ($currentTime - $lastAuthTime) > $reauthInterval) {
            // Store the intended URL
            Session::put('intended_url', $request->url());
            Session::put('requires_reauthentication', true);
            
            return redirect('/reauthenticate');
        }

        // Update the last auth time
        Session::put('last_auth_time', $currentTime);

        return $next($request);
    }
}
