<?php

namespace App\Sla\Services;

use App\Models\SlaRule;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\SlaRuleFilter;
use App\Shared\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SlaRuleService extends BaseService
{

    public function index(Request $request)
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new SlaRuleFilter($request)
        );
    }

    public function show(
        SlaRule $slaRule
    ): SlaRule {

        return $this->baseQuery()
            ->findOrFail($slaRule->id);
    }

    public function create(
        array $data
    ): SlaRule {
        $this->ensureWritable();

        return $this->transaction(function () use ($data) {

            return SlaRule::create($data);
        });
    }

    public function update(
        SlaRule $slaRule,
        array $data
    ): SlaRule {
        $this->ensureWritable();

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

    private function baseQuery(): Builder
    {
        return SlaRule::query()
            ->with([
                'category',
                'priority',
            ]);
    }

    protected function filteredPaginate(
        Builder $query,
        Request $request,
        BaseQueryFilter $filter
    ): LengthAwarePaginator {

        $filter->apply($query);

        return $query->paginate(
            $request->integer('per_page', 10)
        );
    }
}
