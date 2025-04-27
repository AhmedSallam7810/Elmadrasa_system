<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        // web: __DIR__.'/../routes/web.php',
        // health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));


            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Illuminate\Auth\AuthenticationException $e, $request) {
            $currentGuard = $e->guards()[0];
            if ($currentGuard == 'admin') {
                return redirect()->route('admin.login');
            }else{
                return response()->json([
                    'message' => $e->getMessage()
                ], 401);
            }
        });
    })->create();
