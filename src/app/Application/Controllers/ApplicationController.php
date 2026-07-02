<?php

namespace App\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Application\Services\ApplicationService;
use App\Application\Resources\ApplicationResource;
use App\Application\Requests\StoreApplicationRequest;
use App\Application\Requests\UpdateApplicationRequest;
use App\Shared\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationController extends Controller implements HasMiddleware
{
    public function __construct(
        private ApplicationService $service
    ) {}

    public function index(Request $request)
    {
        $applications = $this->service->index($request);

        return ApiResponse::paginated(

            ApplicationResource::collection(
                $applications
            ),

            'Applications retrieved successfully.'

        );
    }

    public function show(
        Application $application
    ) {

        return ApiResponse::success(

            new ApplicationResource(

                $this->service->show(
                    $application
                )

            ),

            'Application retrieved successfully.'

        );
    }

    public function store(
        StoreApplicationRequest $request,
    ) {

        $application = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(

            new ApplicationResource(
                $application
            ),

            'Application created successfully.',
            201

        );
    }

    public function update(
        UpdateApplicationRequest $request,
        Application $application,
    ) {

        $application = $this->service->update(
            $application,
            $request->validated()
        );

        return ApiResponse::success(

            new ApplicationResource(
                $application
            ),

            'Application updated successfully.'

        );
    }

    public function destroy(
        Application $application,
    ) {

        $this->service->delete(
            $application
        );

        return ApiResponse::success(

            null,

            'Application deleted successfully.'

        );
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:application.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:application.create',
                only: ['store']
            ),

            new Middleware(
                'permission:application.update',
                only: ['update']
            ),

            new Middleware(
                'permission:application.delete',
                only: ['destroy']
            ),

        ];
    }
}