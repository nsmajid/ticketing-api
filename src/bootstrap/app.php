<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        $exceptions->render(
            function (
                ModelNotFoundException $e,
                $request
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.'
                ], 404);
            }
        );

        $exceptions->render(
            function (
                ValidationException $e,
                $request
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
        );

        $exceptions->render(
            function (
                NotFoundHttpException $e,
                $request
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint not found.'
                ], 404);
            }
        );

        $exceptions->render(function (
            HttpException $e,
            $request
        ) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Request failed.'
            ], $e->getStatusCode());
        });

        $exceptions->render(function (
            UnauthorizedException $e,
            $request
        ) {

            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        });

        $exceptions->render(
            function (
                Throwable $e,
                $request
            ) {

                if (app()->environment([
                    'local',
                    'development',
                    'staging',

                ])) {
                    return null;
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Internal server error.'
                ], 500);
            }
        );
    })->create();
