<?php

namespace App\TicketWaitingFor\Services;

use App\Models\TicketProgress;
use App\Models\TicketWaitingFor;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketWaitingForFilter;
use App\Shared\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketWaitingForService extends BaseService
{
    /**
     * Display listing.
     */
    public function index(
        Request $request
    ): LengthAwarePaginator {

        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new TicketWaitingForFilter($request)
        );

    }

    /**
     * Display detail.
     */
    public function show(
        TicketWaitingFor $ticketWaitingFor
    ): TicketWaitingFor {

        return $this->baseQuery()
            ->findOrFail(
                $ticketWaitingFor->id
            );

    }

    /**
     * Create.
     */
    public function create(
        array $data
    ): TicketWaitingFor {

        return TicketWaitingFor::create(
            $data
        );

    }

    /**
     * Update.
     */
    public function update(
        TicketWaitingFor $ticketWaitingFor,
        array $data
    ): TicketWaitingFor {

        $this->ensureCodeCanBeUpdated(
            $ticketWaitingFor,
            $data
        );

        $ticketWaitingFor->update(
            $data
        );

        return $ticketWaitingFor->fresh();

    }

    /**
     * Delete.
     */
    public function delete(
        TicketWaitingFor $ticketWaitingFor
    ): void {

        $this->ensureDeletable(
            $ticketWaitingFor
        );

        $ticketWaitingFor->delete();

    }

    /**
     * Ensure code can be updated.
     */
    private function ensureCodeCanBeUpdated(
        TicketWaitingFor $ticketWaitingFor,
        array $data
    ): void {

        if (
            ! array_key_exists('code', $data)
        ) {
            return;
        }

        if (
            $ticketWaitingFor->code ===
            $data['code']
        ) {
            return;
        }

        if (
            class_exists(TicketProgress::class)
            && TicketProgress::query()
                ->where(
                    'ticket_waiting_for_id',
                    $ticketWaitingFor->id
                )
                ->exists()
        ) {

            abort(
                422,
                'Waiting For code cannot be changed because it is already used.'
            );

        }

    }

    /**
     * Ensure data can be deleted.
     */
    private function ensureDeletable(
        TicketWaitingFor $ticketWaitingFor
    ): void {

        if (
            class_exists(TicketProgress::class)
            && TicketProgress::query()
                ->where(
                    'ticket_waiting_for_id',
                    $ticketWaitingFor->id
                )
                ->exists()
        ) {

            abort(
                422,
                'Waiting For cannot be deleted because it is already used.'
            );

        }

    }

    /**
     * Base query.
     */
    private function baseQuery(): Builder
    {
        return TicketWaitingFor::query();
    }

    /**
     * Filtered paginate.
     */
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