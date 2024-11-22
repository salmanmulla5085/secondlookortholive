<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckUser
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
            return redirect('/login')->with('error', 'Please Login.');
        }        
        
        // Allow the request to proceed
        return $next($request);
    }
}