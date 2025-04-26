<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 404 Not Found
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Record not found.'
                ], 404);
            }
        });

        // 401 Unauthorized
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Unauthorized access.'
                ], 401);
            }
        });

        // 403 Forbidden
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Forbidden access.'
                ], 403);
            }
        });

        // 422 Unprocessable Entity
        $exceptions->render(function (UnprocessableEntityHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Unprocessable entity, invalid data.'
                ], 422);
            }
        });

        // 500 Internal Server Error
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() === 500 && $request->is('api/*')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Internal server error.'
                ], 500);
            }
        });

        // 429 Too Many Requests
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Too many requests, please try again later.'
                ], 429);
            }
        });

        // Dinamik xatoliklarni qaytarish (Boshqa xatoliklar uchun)
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'An error occurred.'
                ], $e->getStatusCode() ?: 500);
            }
        });
    })
    ->create();
