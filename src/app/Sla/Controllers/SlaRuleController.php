<?php

namespace App\Sla\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SlaRule;
use App\Shared\Responses\ApiResponse;
use App\Sla\Requests\StoreSlaRuleRequest;
use App\Sla\Requests\UpdateSlaRuleRequest;
use App\Sla\Resources\SlaRuleResource;
use App\Sla\Services\SlaRuleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SlaRuleController extends Controller implements HasMiddleware
{
    public function __construct(
        private SlaRuleService $service
    ) {}

    public function index(Request $request)
    {
        $rules = $this->service->index($request);

        return ApiResponse::paginated(

            SlaRuleResource::collection(
                $rules
            ),

            'SLA rules retrieved successfully.'

        );
    }

    public function show(
        SlaRule $slaRule
    ) {
        return ApiResponse::success(
            new SlaRuleResource(
                $this->service->show(
                    $slaRule
                )
            ),
            'SLA rule retrieved successfully.'
        );
    }

    public function store(
        StoreSlaRuleRequest $request
    ) {
        $rule = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new SlaRuleResource($rule),
            'SLA rule created successfully.',
            201
        );
    }

    public function update(
        UpdateSlaRuleRequest $request,
        SlaRule $slaRule
    ) {
        $rule = $this->service->update(
            $slaRule,
            $request->validated()
        );

        return ApiResponse::success(
            new SlaRuleResource($rule),
            'SLA rule updated successfully.'
        );
    }

    public function destroy(
        SlaRule $slaRule,
    ) {
        $this->service->delete($slaRule);

        return ApiResponse::success(
            null,
            'SLA rule deleted successfully.'
        );
    }


    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:sla.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:sla.manage',
                only: ['store', 'update', 'destroy']
            ),

        ];
    }
}
