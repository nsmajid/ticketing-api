<?php

namespace App\Application\Services;

use App\Models\Application;
use App\Shared\Filters\ApplicationFilter;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplicationService extends BaseService
{
    /**
     * Get application list.
     */
    public function index(Request $request)
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new ApplicationFilter($request)
        );
    }

    /**
     * Get application detail.
     */
    public function show(Application $application): Application
    {
        return $this->baseQuery()
            ->findOrFail($application->id);
    }

    /**
     * Create application.
     */
    public function create(array $data): Application
    {

        return $this->transaction(function () use ($data) {

            return Application::create($data);

        });
    }

    /**
     * Update application.
     */
    public function update(
        Application $application,
        array $data
    ): Application {


        return $this->transaction(function () use ($application, $data) {

            $application->update($data);

            return $this->show($application);

        });
    }

    /**
     * Delete application.
     */
    public function delete(Application $application): void
    {

        $this->deleteModel($application);
    }

    /**
     * Base query.
     */
    private function baseQuery(): Builder
    {
        return Application::query();
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