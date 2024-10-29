<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    
     public function handle(Request $request, Closure $next)
     {
         $response = $next($request);
     
        
         $response->headers->set('Content-Security-Policy', "script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com;");
         $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
         $response->headers->set('X-Content-Type-Options', 'nosniff');
         $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
         
         // Update Content Security Policy
         $csp = "default-src 'self'; " .
                "script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net 'unsafe-inline'; " .
                "style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; " .
                "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
                "img-src 'self' data:; " .
                "object-src 'none'; " .
                "frame-ancestors 'none';";
         
         $response->headers->set('Content-Security-Policy', $csp);
         
         return $response;
     }
     
}
