<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request): JsonResponse {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to perform this action.',
                'errorCode' => 403
            ], 403);
        });

        $exceptions->render(function (ValidationException $e, Request $request): JsonResponse {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'errorCode' => 422
            ], 422);
        });
    })->create();
