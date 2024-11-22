<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = session('user', []);
        
        // Check if the user session exists
        if (empty($user)) {
            return redirect('login/doctor')->with('error', 'Access restricted to doctors only.');
        }
        
        // Check if the user is a doctor
        if (!empty($user['user_type']) && $user['user_type'] !== 'doctor') {
            return redirect('login/doctor')->with('error', 'Access restricted to doctors only.');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
