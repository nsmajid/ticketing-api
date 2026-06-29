<?php

namespace App\Sla\Services;

use App\Models\SlaRule;
use App\Shared\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SlaRuleService extends BaseService
{

    public function index(Request $request): LengthAwarePaginator
    {
        return SlaRule::query()
            ->with([
                'category',
                'priority',
            ])
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            );
    }

    public function show(
        SlaRule $slaRule
    ): SlaRule {

        return SlaRule::query()
            ->with([
                'category',
                'priority',
            ])
            ->findOrFail($slaRule->id);
    }

    public function create(
        array $data
    ): SlaRule {
        return $this->transaction(function () use ($data) {

            return SlaRule::create($data);
        });
    }

    public function update(
        SlaRule $slaRule,
        array $data
    ): SlaRule {

        return $this->transaction(function () use (
            $slaRule,
            $data
        ) {

            $slaRule->update($data);

            return $slaRule->fresh();
        });
    }

    public function delete(
        SlaRule $slaRule
    ): void {

        $this->deleteModel($slaRule);
    }
}
