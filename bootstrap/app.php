<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Http\Responses\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\LogRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render all exceptions as JSON
        $exceptions->render(function (\Throwable $e, $request) {

            // Validation errors
            // if ($e instanceof ValidationException) {
            //     return ApiResponse::error('Validation failed', 422);
            // }

            // Authentication errors
            // if ($e instanceof AuthenticationException) {
            //     return ApiResponse::error('Unauthenticated', 401);
            // }

            // Model not found
            if ($e instanceof ModelNotFoundException) {
                return ApiResponse::error('Resource not found', 404);
            }

            // Route not found
            if ($e instanceof RouteNotFoundException) {
                return ApiResponse::error('Route not found', 404);
            }

            // HTTP exceptions (404, 405, etc.)
            if ($e instanceof HttpExceptionInterface) {
                return ApiResponse::error($e->getMessage() ?: 'HTTP error', $e->getStatusCode());
            }

            // Default fallback for other exceptions
            return ApiResponse::error($e->getMessage() ?: 'Something went wrong', 500);
        });
    })->create();
