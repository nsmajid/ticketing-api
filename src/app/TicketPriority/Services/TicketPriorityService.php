<?php

namespace App\TicketPriority\Services;

use App\Models\TicketPriority;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketPriorityFilter;
use App\Shared\Services\BaseService;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketPriorityService extends BaseService
{

    public function index(Request $request)
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new TicketPriorityFilter($request)
        );
    }

    public function show(
        TicketPriority $priority
    ): TicketPriority {

        return $this->baseQuery()
            ->findOrFail($priority->id);
    }

    public function create(
        array $data
    ): TicketPriority {
        $this->ensureWritable();

        return $this->transaction(function () use ($data) {

            return TicketPriority::create($data);
        });
    }

    public function update(
        TicketPriority $priority,
        array $data
    ): TicketPriority {
        $this->ensureWritable();

        return $this->transaction(function () use (
            $priority,
            $data
        ) {

            $priority->update($data);

            return $priority->fresh();
        });
    }

    public function delete(
        TicketPriority $priority
    ): void {

        $this->deleteModel($priority);
    }

    private function baseQuery(): Builder
    {
        return TicketPriority::query();
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
