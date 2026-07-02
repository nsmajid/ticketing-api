<?php

namespace App\TicketCategory\Services;

use App\Models\TicketCategory;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketCategoryFilter;
use App\Shared\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketCategoryService extends BaseService
{
    public function index(Request $request)
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new TicketCategoryFilter($request)
        );
    }

    public function show(
        TicketCategory $category
    ): TicketCategory {

        return $this->baseQuery()
            ->findOrFail($category->id);
    }

    public function create(
        array $data
    ): TicketCategory {
        $this->ensureWritable();

        return $this->transaction(function () use ($data) {

            return TicketCategory::create($data);
        });
    }

    public function update(
        TicketCategory $category,
        array $data
    ): TicketCategory {
        $this->ensureWritable();

        return $this->transaction(function () use (
            $category,
            $data
        ) {

            $category->update($data);

            return $category->fresh();
        });
    }

    public function delete(
        TicketCategory $category
    ): void {

        $this->deleteModel($category);
    }

    private function baseQuery(): Builder
    {
        return TicketCategory::query();
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
