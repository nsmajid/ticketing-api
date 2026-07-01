<?php

namespace App\ApplicationFeature\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApplicationFeature;
use App\ApplicationFeature\Requests\StoreApplicationFeatureRequest;
use App\ApplicationFeature\Requests\UpdateApplicationFeatureRequest;
use App\ApplicationFeature\Resources\ApplicationFeatureResource;
use App\ApplicationFeature\Services\ApplicationFeatureService;
use App\Shared\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationFeatureController extends Controller implements HasMiddleware
{
    public function __construct(
        private ApplicationFeatureService $service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $applicationFeatures = $this->service->index($request);

        return ApiResponse::paginated(

            ApplicationFeatureResource::collection(
                $applicationFeatures
            ),

            'Application features retrieved successfully.'

        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(
        StoreApplicationFeatureRequest $request,
    ) {

        $applicationFeature = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(

            new ApplicationFeatureResource(
                $applicationFeature
            ),

            'Application feature created successfully.',
            201

        );
    }

    /**
     * Display the specified resource.
     */
    public function show(
        ApplicationFeature $applicationFeature,
    ) {

        return ApiResponse::success(

            new ApplicationFeatureResource(

                $this->service->show(
                    $applicationFeature
                )

            ),

            'Application feature retrieved successfully.'

        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        UpdateApplicationFeatureRequest $request,
        ApplicationFeature $applicationFeature,
    ) {

        $applicationFeature = $this->service->update(
            $applicationFeature,
            $request->validated()
        );

        return ApiResponse::success(

            new ApplicationFeatureResource(
                $applicationFeature
            ),

            'Application feature updated successfully.'

        );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        ApplicationFeature $applicationFeature,
    ) {

        $this->service->delete(
            $applicationFeature
        );

        return ApiResponse::success(

            null,

            'Application feature deleted successfully.'

        );
    }

    /**
     * Controller Middleware.
     */
    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:application-feature.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:application-feature.create',
                only: ['store']
            ),

            new Middleware(
                'permission:application-feature.update',
                only: ['update']
            ),

            new Middleware(
                'permission:application-feature.delete',
                only: ['destroy']
            ),

        ];
    }
}