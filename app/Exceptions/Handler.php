<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @    var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Check if the request is an API request or expects JSON
        if ($request->is('api/*') || $request->expectsJson()) {
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                // Return a custom JSON response for 404 errors
                return response()->json([
                    'status' => 'error',
                    'message' => 'API route not found',
                ], 404);
            }
            
            // You can handle other exceptions similarly here, such as 500 errors or validation failures.
        }

        // Check if the 'user' session object is missing (session expired)
        if ($request->hasSession() && $request->session()->has('key')) {
            // Access session safely
        
        if (!$request->session()->has('user')) {
            // If the session is expired, redirect to the login page with a message
            return redirect()->route('/')->withErrors([
                'message' => 'Your session has expired. Please log in again.',
            ]);
        }
        }
        
        // For non-API requests, use the default behavior
        return parent::render($request, $exception);
    }
}
