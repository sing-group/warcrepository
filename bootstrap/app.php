<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\ApiAuthenticate;
use App\Http\Middleware\LangMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            // API (sustituye a Dingo): mismas URLs bajo /api, sin sesión ni CSRF.
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Rutas web con el espacio de nombres de controladores de Laravel 5.3.
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            LangMiddleware::class,
        ]);

        $middleware->alias([
            'AdminAuth' => AdminAuth::class,
            'guest' => RedirectIfAuthenticated::class,
            'api.auth' => ApiAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Las rutas de la API responden con el formato de errores de Dingo:
        // {"message": "...", "status_code": 401}
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api', 'api/*')) {
                return null;
            }

            $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $headers = $e instanceof HttpExceptionInterface ? $e->getHeaders() : [];

            $message = $e->getMessage();
            if ($message === '') {
                $message = sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode] ?? '');
            }

            $body = ['message' => $message];
            if ($e->getCode()) {
                $body['code'] = $e->getCode();
            }
            $body['status_code'] = $statusCode;

            if (config('app.api_debug')) {
                $body['debug'] = [
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'class' => get_class($e),
                    'trace' => explode("\n", $e->getTraceAsString()),
                ];
            }

            return response()->json($body, $statusCode, $headers);
        });

        // Mismo comportamiento que el Handler de Laravel 5.3.
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest('login');
        });
    })->create();
