<?php

use App\Shared\Responses\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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
                return ApiResponse::error(
                    'Data not found.',
                    null,
                    404
                );
            }
        );

        $exceptions->render(
            function (
                ValidationException $exception,
                $request
            ) {

                return ApiResponse::error(
                    'Validation failed.',
                    $exception->errors(),
                    422
                );
            }
        );

        $exceptions->render(
            function (
                NotFoundHttpException $e,
                $request
            ) {

                return ApiResponse::error(
                    'Endpoint not found.',
                    null,
                    404
                );
            }
        );

        $exceptions->render(function (
            HttpException $exception,
            $request
        ) {

            return ApiResponse::error(
                $exception->getMessage() ?: 'HTTP Error.',
                null,
                $exception->getStatusCode()
            );
        });

        $exceptions->render(function (
            UnauthorizedException $e,
            $request
        ) {

            return ApiResponse::error(
                'You do not have permission to perform this action.',
                null,
                403
            );
        });

        $exceptions->render(function (
            QueryException $e,
            $request
        ) {

            $uniqueMessages = [
                'users_email_unique'
                => 'Email already exists.',

                'users_username_unique'
                => 'Username already exists.',

                'ticket_categories_name_unique'
                => 'Ticket Category already exists.',

                'ticket_priorities_name_unique'
                => 'Ticket Priority already exists.',

                'ticket_statuses_code_unique'
                => 'Ticket Status code already exists.',

                'sla_rules_ticket_category_id_ticket_priority_id_unique'
                => 'SLA Rule for this category and priority already exists.',

            ];

            if ($e->getCode() == 23000) {
                foreach ($uniqueMessages as $index => $message) {

                    if (str_contains($e->getMessage(), $index)) {

                        return ApiResponse::error(
                            'Duplicate data found.',
                            null,
                            409
                        );
                    }
                }
            }
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

                return ApiResponse::error(
                    'Internal server error.',
                    null,
                    500
                );
            }
        );
    })->create();
