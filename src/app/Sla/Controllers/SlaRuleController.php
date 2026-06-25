<?php

namespace App\Sla\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SlaRule;
use App\Sla\Requests\StoreSlaRuleRequest;
use App\Sla\Requests\UpdateSlaRuleRequest;
use App\Sla\Resources\SlaRuleResource;
use App\Sla\Services\SlaRuleService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SlaRuleController extends Controller implements HasMiddleware
{
    public function __construct(
        private SlaRuleService $slaRuleService
    ) {}

    public function index()
    {
        return SlaRuleResource::collection(
            SlaRule::all()
        );
    }

    public function show(
        SlaRule $slaRule
    ): SlaRuleResource {
        return new SlaRuleResource(
            $slaRule
        );
    }

    public function store(
        StoreSlaRuleRequest $request
    ): SlaRuleResource {
        return new SlaRuleResource(
            $this->slaRuleService->create(
                $request->all()
            )
        );
    }

    public function update(
        UpdateSlaRuleRequest $request,
        SlaRule $slaRule
    ): SlaRuleResource {
        return new SlaRuleResource(
            $this->slaRuleService->update(
                $slaRule,
                $request->all()
            )
        );
    }

    public function destroy(
        SlaRule $slaRule,
        SlaRuleService $slaRuleService
    ) {
        $slaRuleService->delete(
            $slaRule
        );

        return response()->json([
            'success' => true,
            'message' => 'Sla rule deleted successfully'
        ]);
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
                only: ['store','update', 'destroy']
            ),
           
        ];
    }
}
