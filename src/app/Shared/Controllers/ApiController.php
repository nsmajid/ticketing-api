<?php

namespace App\Shared\Controllers;

use App\Shared\Responses\ApiResponse;

abstract class ApiController
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ) {
        return ApiResponse::success(
            $data,
            $message,
            $status
        );
    }

    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ) {
        return ApiResponse::error(
            $message,
            $status,
            $errors
        );
    }
}