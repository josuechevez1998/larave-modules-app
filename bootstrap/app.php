<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TeamsPermission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            TeamsPermission::class,
            SetLocale::class,
            SecurityHeaders::class,
        ]);

        $middleware->api(append: [
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => __('errors.validation'),
                    'code' => 'validation_error',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return friendly_error_response($request, __('errors.forbidden'), 'forbidden', 403);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage() ?: __('Unauthenticated.'),
                    'code' => 'unauthenticated',
                ], 401);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return friendly_error_response($request, __('errors.not_found'), 'not_found', 404);
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            Log::error('Database query exception', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            return friendly_error_response($request, __('errors.database'), 'database_error', 500);
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($e instanceof ValidationException
                || $e instanceof AuthorizationException
                || $e instanceof AuthenticationException
                || $e instanceof NotFoundHttpException
                || $e instanceof QueryException
                || $e instanceof HttpExceptionInterface) {
                return null;
            }

            Log::error($e->getMessage(), ['exception' => $e]);

            if (config('app.debug') && ! ($request->expectsJson() || $request->is('api/*'))) {
                return null;
            }

            $message = config('app.debug')
                ? $e->getMessage()
                : __('errors.unexpected');

            return friendly_error_response($request, $message, 'unexpected_error', 500);
        });
    })->create();

/**
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
 */
if (! function_exists('friendly_error_response')) {
    function friendly_error_response(Request $request, string $message, string $code, int $status)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'code' => $code,
            ], $status);
        }

        if ($request->hasSession()) {
            $request->session()->flash('error', $message);
        }

        $view = match ($status) {
            403 => 'errors.403',
            404 => 'errors.404',
            default => 'errors.500',
        };

        return response()->view($view, ['message' => $message], $status);
    }
}
