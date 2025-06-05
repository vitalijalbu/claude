<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Handle API exceptions and return JSON responses
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function handle(Throwable $exception, Request $request)
    {
        // Only handle API requests
        if (! ($request->expectsJson() || $request->is('api/*'))) {
            return null;
        }

        // ValidationException
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $exception->errors(),
            ], 422);
        }

        // ModelNotFoundException
        if ($exception instanceof ModelNotFoundException) {
            $modelName = mb_strtolower(class_basename($exception->getModel()));

            return response()->json([
                'message' => "Unable to find {$modelName} with the specified identifier.",
            ], 404);
        }

        // AuthenticationException
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // AuthorizationException
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'This action is unauthorized.',
            ], 403);
        }

        // NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'The requested resource was not found.',
            ], 404);
        }

        // MethodNotAllowedHttpException
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'The specified method is not allowed.',
            ], 405);
        }

        // ThrottleRequestsException
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }

        // HttpException
        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        // Handle all other exceptions
        // For local development, include more details
        if (config('app.debug')) {
            return response()->json([
                'message' => 'Server Error',
                'exception' => get_class($exception),
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ], 500);
        }

        // Production error response
        return response()->json([
            'message' => 'Server Error',
        ], 500);
    }
}
