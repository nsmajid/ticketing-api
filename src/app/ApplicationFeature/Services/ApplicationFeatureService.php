<?php

namespace App\ApplicationFeature\Services;

use App\Models\ApplicationFeature;
use App\Shared\Filters\ApplicationFeatureFilter;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplicationFeatureService extends BaseService
{
    /**
     * Get application feature list.
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new ApplicationFeatureFilter($request)
        );
    }

    /**
     * Get application feature detail.
     */
    public function show(
        ApplicationFeature $applicationFeature
    ): ApplicationFeature {

        return $this->baseQuery()
            ->findOrFail($applicationFeature->id);
    }

    /**
     * Create application feature.
     */
    public function create(
        array $data
    ): ApplicationFeature {

        $this->ensureWritable();

        return $this->transaction(function () use ($data) {

            return ApplicationFeature::create($data);
        });
    }

    /**
     * Update application feature.
     */
    public function update(
        ApplicationFeature $applicationFeature,
        array $data
    ): ApplicationFeature {


        return $this->transaction(function () use (
            $applicationFeature,
            $data
        ) {

            $applicationFeature->update($data);

            return $this->show(
                $applicationFeature
            );
        });
    }

    /**
     * Delete application feature.
     */
    public function delete(
        ApplicationFeature $applicationFeature
    ): void {

        $applicationFeature->delete();
    }

    /**
     * Base query.
     */
    private function baseQuery(): Builder
    {
        return ApplicationFeature::query()

            ->with([
                'application',
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
