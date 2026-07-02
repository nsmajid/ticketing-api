<?php

namespace App\Shared\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function paginated(
        AnonymousResourceCollection $resource,
        string $message = 'Success.'
    ): JsonResponse {

        $response = $resource->response()->getData(true);

        return response()->json([

            'success' => true,

            'message' => $message,

            'data' => $response['data'],

            'meta' => $response['meta'],

            'links' => $response['links'],

        ]);
    }


    public static function error(
        string $message = 'Error.',
        mixed $errors = null,
        int $status = 400
    ): JsonResponse {

        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json(
            $response,
            $status
        );
    }
}
