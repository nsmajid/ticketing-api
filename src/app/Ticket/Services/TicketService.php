<?php

namespace App\Ticket\Services;


use App\Models\SlaRule;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Shared\Enums\Ticket\TicketStatusCode;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketFilter;
use App\Shared\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketService extends BaseService
{
    /**
     * Display listing.
     */
    public function index(Request $request): LengthAwarePaginator
    {
        return $this->filteredPaginate(
            $this->baseQuery(),
            $request,
            new TicketFilter($request)
        );
    }

    /**
     * Show detail.
     */
    public function show(
        Ticket $ticket
    ): Ticket {

        return $this->baseQuery()
            ->findOrFail($ticket->id);
    }

    /**
     * Store ticket.
     */
    public function create(
        array $data
    ): Ticket {

        return $this->transaction(function () use ($data) {

            $slaRule = $this->resolveSlaRule($data);

            $statusId = TicketStatus::query()

                ->where(
                    'code',
                    TicketStatusCode::Draft->value
                )

                ->value('id');

            $ticket = Ticket::create([

                ...$data,

                'ticket_number' => $this->generateTicketNumber(),

                'requester_id' => auth()->id(),

                'ticket_status_id' => $statusId,

                'submitted_at' => null,

                'response_due_at' => null,

                'resolution_due_at' => null,

            ]);

            return $this->show($ticket);
        });
    }

    /**
     * Update ticket.
     */
    public function update(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensureEditable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update([

                ...$data,

                'response_due_at' => null,

                'resolution_due_at' => null,

            ]);

            return $this->show($ticket);
        });
    }


    /**
     * Submit ticket.
     */
    public function submit(
        Ticket $ticket
    ): Ticket {

        $this->ensureDraft($ticket);

        return $this->transaction(function () use ($ticket) {

            $statusId = TicketStatus::query()

                ->where(
                    'code',
                    TicketStatusCode::Submitted->value
                )

                ->value('id');

            $slaRule = $this->resolveSlaRule([
                'ticket_category_id' => $ticket->ticket_category_id,
                'ticket_priority_id' => $ticket->ticket_priority_id,
            ]);

            $submittedAt = now();

            $ticket->update([

                'ticket_status_id' => $statusId,

                'submitted_at' => $submittedAt,

                'response_due_at' => $submittedAt
                    ->copy()
                    ->addHours($slaRule->response_hours),

                'resolution_due_at' => $submittedAt
                    ->copy()
                    ->addHours($slaRule->resolution_hours),

            ]);

            return $this->show($ticket);
        });
    }

    /**
     * Delete ticket.
     */
    public function delete(
        Ticket $ticket
    ): void {

        $this->ensureDeletable($ticket);

        $ticket->delete();
    }

    /**
     * Base query.
     */
    private function baseQuery(): Builder
    {
        return Ticket::query()

            ->with([

                'requester',

                'applicationFeature.application',

                'category',

                'priority',

                'status',

            ]);
    }

    /**
     * Resolve SLA.
     */
    private function resolveSlaRule(
        array $data
    ): SlaRule {

        return SlaRule::query()

            ->where(
                'ticket_category_id',
                $data['ticket_category_id']
            )

            ->where(
                'ticket_priority_id',
                $data['ticket_priority_id']
            )

            ->firstOrFail();
    }

    

    /**
     * Ensure ticket still submitted.
     */
    private function ensureSubmitted(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !== TicketStatusCode::Submitted->value
        ) {

            abort(
                422,
                'Only submitted ticket can be modified.'
            );
        }
    }

    private function ensureDraft(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !== TicketStatusCode::Draft->value
        ) {

            abort(

                422,

                'Only draft ticket can be submitted.'

            );
        }
    }

    /**
     * Generate ticket number.
     */
    private function generateTicketNumber(): string
    {
        $prefix = now()->format('Ymd');

        $lastTicket = Ticket::query()

            ->whereDate(
                'created_at',
                today()
            )

            ->latest('id')

            ->first();

        $sequence = $lastTicket
            ? ((int) substr($lastTicket->ticket_number, -5)) + 1
            : 1;

        return sprintf(
            'TCK-%s-%05d',
            $prefix,
            $sequence
        );
    }

    private function ensureEditable(
        Ticket $ticket
    ): void {

        if (! in_array(

            $ticket->status->code,

            [
                TicketStatusCode::Draft->value,

                TicketStatusCode::Rejected->value,
            ]
        )) {
            abort(
                422,
                'Ticket cannot be modified.'
            );
        }
    }

    private function ensureDeletable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !== TicketStatusCode::Draft->value
        ) {

            abort(
                422,
                'Only draft ticket can be deleted.'
            );
        }
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
