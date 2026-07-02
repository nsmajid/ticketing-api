<?php

namespace App\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Shared\Responses\ApiResponse;
use App\User\Requests\StoreUserRequest;
use App\User\Requests\UpdateUserRequest;
use App\User\Resources\UserResource;
use App\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class UserController extends Controller implements HasMiddleware
{

    public function __construct(
        protected UserService $service
    ) {}

    public function index(Request $request)
    {

        $users = $this->service->index($request);

        return ApiResponse::paginated(
            UserResource::collection($users),
            'Users retrieved successfully.'
        );
    }

    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new UserResource($user),
            'User created successfully.',
            201
        );
    }


    public function show(User $user)
    {
        return ApiResponse::success(

            new UserResource(
                $this->service->show($user)
            ),

            'User retrieved successfully.'

        );
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UserService $service
    ) {
        $user = $service->update(
            $user,
            $request->validated()
        );

        return ApiResponse::success(
            new UserResource($user),
            'User updated successfully.'
        );
      
    }


    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:user.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:user.create',
                only: ['store']
            ),

            new Middleware(
                'permission:user.update',
                only: ['update']
            ),

        ];
    }
}
