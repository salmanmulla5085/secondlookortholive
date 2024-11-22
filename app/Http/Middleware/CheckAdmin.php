<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckAdmin
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
        $admin = session('admin', []);
        
        // Check if the user session exists
        if (empty($admin)) {
            // return redirect('/admin_login')->with('error', 'Access restricted to admin only.');
            return redirect('/admin_login')->with('success', 'Please Login!');
        }
                
        // Allow the request to proceed
        return $next($request);
    }
}