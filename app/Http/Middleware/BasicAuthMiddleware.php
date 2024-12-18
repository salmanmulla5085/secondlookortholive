<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $AUTH_USER = env('API_BASIC_AUTH_USER', 'admin');
        $AUTH_PASS = env('API_BASIC_AUTH_PASSWORD', 'password');

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED, ['WWW-Authenticate' => 'Basic']);
        }

        // $username = env('BASIC_AUTH_USERNAME');
        // $password = env('BASIC_AUTH_PASSWORD');        
        // if ($request->getUser() !== $username || $request->getPassword() !== $password) {
        //     return response('Unauthorized', 401)->header('WWW-Authenticate', 'Basic');
        // }

        return $next($request);
    }
}
