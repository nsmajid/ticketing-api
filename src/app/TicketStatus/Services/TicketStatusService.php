<?php

namespace App\TicketStatus\Services;

use App\Models\TicketStatus;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketStatusFilter;
use App\Shared\Services\BaseService;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TicketStatusService extends BaseService
{
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new TicketStatusFilter($request)
        );
    }

    public function show(
        TicketStatus $status
    ): TicketStatus {
        return $this->baseQuery()
            ->findOrFail($status->id);
    }
    /* Create */
    public function create(
        array $data
    ): TicketStatus {
        $this->ensureWritable();

        return $this->transaction(function () use ($data) {

            if (!empty($data['is_initial'])) {

                $this->resetInitialStatus();
            }

            return TicketStatus::create($data);
        });
    }

    // UPDATE
    public function update(
        TicketStatus $ticketStatus,
        array $data
    ): TicketStatus {
        $this->ensureWritable();

        return $this->transaction(function () use (
            $ticketStatus,
            $data
        ) {

            if (!empty($data['is_initial'])) {

                $this->resetInitialStatus(
                    $ticketStatus->id
                );
            }

            $ticketStatus->update($data);

            return $ticketStatus->fresh();
        });
    }

    // DELETE
    public function delete(
        TicketStatus $ticketStatus
    ): void {
        $this->deleteModel($ticketStatus);
    }

    private function resetInitialStatus(
        ?int $ignoreId = null
    ): void {

        TicketStatus::query()
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->update([
                'is_initial' => false,
            ]);
    }

    private function resetBooleanFlag(
        string $column,
        ?int $ignoreId = null
    ): void {
        TicketStatus::query()
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->update([
                $column => false,
            ]);
    }

    private function baseQuery(): Builder
    {
        return TicketStatus::query();
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
