<?php

namespace App\Ticket\Services;


use App\Models\SlaRule;
use App\Models\Ticket;
use App\Models\TicketAssignableRole;
use App\Models\TicketAssignment;
use App\Models\TicketProgress;
use App\Models\TicketStatus;
use App\Models\User;
use App\Shared\Enums\System\Permission;
use App\Shared\Enums\Ticket\TicketProgressAction;
use App\Shared\Enums\Ticket\TicketStatusCode;
use App\Shared\Enums\Ticket\TicketTimelineAction;
use App\Shared\Filters\BaseQueryFilter;
use App\Shared\Filters\TicketFilter;
use App\Shared\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

                'closed_by' => null,

                'closed_at' => null,

                'close_notes' => null,

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

        $statusId = $ticket->ticket_status_id;

        if (
            $ticket->status->code ===
            TicketStatusCode::Rejected->value
        ) {

            $statusId = TicketStatus::query()

                ->where(
                    'code',
                    TicketStatusCode::Draft->value
                )

                ->value('id');
        }

        return $this->transaction(function () use (
            $ticket,
            $statusId,
            $data
        ) {

            $ticket->update([

                ...$data,

                'ticket_status_id' => $statusId,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
                'response_due_at' => null,
                'resolution_due_at' => null,
                'closed_by' => null,
                'closed_at' => null,
                'close_notes' => null,

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

            $statusId = $this->resolveStatusId(
                TicketStatusCode::Submitted
            );

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
     * Review ticket.
     */
    public function review(
        Ticket $ticket,
        array $data
    ): Ticket {
        $this->ensureReviewable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $statusId = $this->resolveStatusId(
                $data['result']
            );

            $ticket->update([

                'ticket_status_id' => $statusId,

                'reviewed_by' => auth()->id(),

                'reviewed_at' => now(),

                'review_notes' => $data['review_notes'],

            ]);

            /*
        |--------------------------------------------------------------------------
        | Ticket Activity
        |--------------------------------------------------------------------------
        | Step 18
        */

            return $this->show($ticket);
        });
    }

    /**
     * Create ticket assignment.
     */
    public function assign(
        Ticket $ticket,
        array $data
    ): Ticket {
        $currentAssignment = $ticket->activeAssignment;

        /*
    |--------------------------------------------------------------------------
    | Business Rule
    |--------------------------------------------------------------------------
    */

        $this->ensureAssignableUser(
            $data['assigned_to']
        );

        if ($currentAssignment) {

            $this->ensureReassignable($ticket);

            abort_unless(
                auth()->user()->can(
                    Permission::TicketReassign->value
                ),
                403
            );

            if ($currentAssignment->assigned_to == $data['assigned_to']) {
                abort(
                    422,
                    'Ticket is already assigned to the selected user.'
                );
            }
        } else {

            $this->ensureAssignable($ticket);

            abort_unless(
                auth()->user()->can(
                    Permission::TicketAssign->value
                ),
                403
            );
        }

        return $this->transaction(function () use (
            $ticket,
            $data,
            $currentAssignment
        ) {

            /*
        |--------------------------------------------------------------------------
        | Deactivate Current Assignment
        |--------------------------------------------------------------------------
        */

            if ($currentAssignment) {

                $currentAssignment->update([
                    'is_active' => false,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Create Assignment
        |--------------------------------------------------------------------------
        */

            TicketAssignment::create([

                'ticket_id' => $ticket->id,

                'assigned_to' => $data['assigned_to'],

                'assigned_by' => auth()->id(),

                'assigned_at' => now(),

                'assignment_notes' => $data['assignment_notes'],

                'is_active' => true,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Update Ticket Status
        |--------------------------------------------------------------------------
        */

            if (
                $ticket->status->code ===
                TicketStatusCode::Reviewed->value
            ) {

                $ticket->update([

                    'ticket_status_id' => $this->resolveStatusId(
                        TicketStatusCode::Assigned
                    ),

                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Step 18
        |--------------------------------------------------------------------------
        */

            return $this->show($ticket);
        });
    }

    /**
     * Create ticket progress.
     */
    private function createProgress(
        Ticket $ticket,
        TicketProgressAction $action,
        ?string $progressNotes = null,
        ?int $waitingForId = null,
        ?string $resolutionNotes = null,
    ): void {

        TicketProgress::create([

            'ticket_id' => $ticket->id,

            'ticket_status_id' => $ticket->ticket_status_id,

            'action' => $action->value,

            'ticket_waiting_for_id' => $waitingForId,

            'user_id' => auth()->id(),

            'progress_notes' => $progressNotes,

            'resolution_notes' => $resolutionNotes,

        ]);
    }

    public function startProgress(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensureStartable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update([

                'ticket_status_id' => $this->resolveStatusId(
                    TicketStatusCode::InProgress
                ),

            ]);

            // Refresh agar ticket_status_id terbaru terbaca
            $ticket->refresh();

            $this->createProgress(
                $ticket,
                TicketProgressAction::Start,
                $data['progress_notes'] ?? null,

            );

            return $this->show($ticket);
        });
    }

    public function pending(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensurePendingable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update([

                'ticket_status_id' => $this->resolveStatusId(
                    TicketStatusCode::Pending
                ),

            ]);

            $ticket->refresh();

            $this->createProgress(
                $ticket,
                TicketProgressAction::Pending,
                $data['progress_notes'],
                $data['ticket_waiting_for_id'],
            );

            return $this->show($ticket);
        });
    }

    public function resume(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensureResumable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update([

                'ticket_status_id' => $this->resolveStatusId(
                    TicketStatusCode::InProgress
                ),

            ]);

            $ticket->refresh();

            $this->createProgress(
                $ticket,
                TicketProgressAction::Resume,
                $data['progress_notes'],
            );

            return $this->show($ticket);
        });
    }

    public function resolve(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensureResolvable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update([

                'ticket_status_id' => $this->resolveStatusId(
                    TicketStatusCode::Resolved
                ),

            ]);

            $ticket->refresh();

            $this->createProgress(
                $ticket,
                TicketProgressAction::Resolved,
                null,
                null,
                $data['resolution_notes'],
            );

            return $this->show($ticket);
        });
    }

    /**
     * Accept ticket.
     */
    public function accept(
        Ticket $ticket,
        array $data
    ): Ticket {

        $this->ensureAcceptable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $status = $data['result'] === TicketStatusCode::Closed->value
                ? TicketStatusCode::Closed
                : TicketStatusCode::Assigned;



            if ($status === TicketStatusCode::Closed) {
                $this->createProgress(
                    $ticket,
                    TicketProgressAction::AcceptanceApproved,
                    $data['close_notes'],

                );
            } else {
                $this->createProgress(
                    $ticket,
                    TicketProgressAction::AcceptanceRejected,
                    'Acceptance rejected. ' . $data['close_notes'],

                );
            }

            $ticket->refresh();

            $ticket->update([

                'ticket_status_id' => $this->resolveStatusId($status),

                'closed_by' => $status === TicketStatusCode::Closed
                    ? auth()->id()
                    : null,

                'closed_at' => $status === TicketStatusCode::Closed
                    ? now()
                    : null,

                'close_notes' => $data['close_notes'],

            ]);

            // $this->createProgress(
            //     $ticket,
            //     [
            //         'progress_notes' =>
            //         $status === TicketStatusCode::Closed

            //             ? 'Ticket accepted by client. ' . $data['close_notes']

            //             : 'Acceptance rejected. ' . $data['close_notes'],

            //     ]

            // );

            return $this->show($ticket);
        });
    }

    /**
     * Ticket timeline.
     */
    public function timeline(
        Ticket $ticket
    ): Collection {

        $ticket->load([

            'requester',

            'reviewer',

            'closer',

            'status',

            'assignments.assigner',

            'assignments.assignee',

            'progresses.user',

            'progresses.ticketWaitingFor',

        ]);

        return collect()

            ->merge(
                $this->buildCreatedTimeline($ticket)
            )

            ->merge(
                $this->buildSubmittedTimeline($ticket)
            )

            ->merge(
                $this->buildReviewTimeline($ticket)
            )

            ->merge(
                $this->buildAssignmentTimeline($ticket)
            )

            ->merge(
                $this->buildProgressTimeline($ticket)
            )

            ->merge(
                $this->buildClosedTimeline($ticket)
            )

            ->sortBy('time')

            ->values();
    }

    /**
     * Build timeline item.
     */
    private function buildTimeline(
        mixed $time,
        string $action,
        string $title,
        ?string $description,
        ?User $user = null,
        array $metadata = [],
    ): array {

        return [

            'time' => $time,

            'action' => $action,

            'title' => $title,

            'description' => $description,

            'user' => $user,

            'metadata' => $metadata,

        ];
    }

    /**
     * Build created timeline.
     */
    private function buildCreatedTimeline(
        Ticket $ticket
    ): Collection {



        return collect([
            $this->buildTimeline(

                time: $ticket->created_at,

                action: TicketTimelineAction::Created->value,

                title: 'Ticket Created',

                description: 'Ticket created as Draft.',

                user: $ticket->requester,

            ),
        ]);
    }

    /**
     * Build submitted timeline.
     */
    private function buildSubmittedTimeline(
        Ticket $ticket
    ): Collection {

        if (!$ticket->submitted_at) {

            return collect();
        }

        return collect([

            $this->buildTimeline(

                time: $ticket->submitted_at,

                action: TicketTimelineAction::Submitted->value,

                title: 'Ticket Submitted',

                description: 'Ticket submitted for review.',

                user: $ticket->requester,

            ),

        ]);
    }

    /**
     * Build review timeline.
     */
    private function buildReviewTimeline(
        Ticket $ticket
    ): Collection {

        if (!$ticket->reviewed_at) {

            return collect();
        }

        $action = $ticket->ticket_status_id === $this->resolveStatusId(
            TicketStatusCode::Rejected
        )
            ? TicketTimelineAction::Rejected
            : TicketTimelineAction::Reviewed;

        $title = $action === TicketTimelineAction::Rejected
            ? 'Ticket Rejected'
            : 'Ticket Reviewed';

        return collect([
            $this->buildTimeline(

                time: $ticket->reviewed_at,

                action: $action->value,

                title: $title,

                description: $ticket->review_notes,

                user: $ticket->reviewer,

            ),

        ]);
    }

    /**
     * Build assignment timeline.
     */
    private function buildAssignmentTimeline(
        Ticket $ticket
    ): Collection {

        return $ticket->assignments

            ->values()

            ->map(function (
                TicketAssignment $assignment,
                int $index
            ) {

                $isReassign = $index > 0;

                return $this->buildTimeline(

                    time: $assignment->assigned_at,

                    action: $isReassign
                        ? TicketTimelineAction::Reassigned->value
                        : TicketTimelineAction::Assigned->value,

                    title: $isReassign
                        ? 'Ticket Reassigned'
                        : 'Ticket Assigned',

                    description: $assignment->assignment_notes,

                    user: $assignment->assigner,

                    metadata: [

                        'assigned_to' => $this->buildUserMetadata(
                            $assignment->assignee
                        ),

                    ],

                );
            });
    }

    /**
     * Build user metadata.
     */
    private function buildUserMetadata(
        ?User $user
    ): ?array {

        if (!$user) {

            return null;
        }

        return [

            'id' => $user->id,

            'name' => $user->name,

        ];
    }

    /**
     * Build progress timeline.
     */
    private function buildProgressTimeline(
        Ticket $ticket
    ): Collection {

        return $ticket->progresses

            ->map(function ($progress) {

                $title = match ($progress->action) {

                    TicketProgressAction::Start->value
                    => 'Start Progress',

                    TicketProgressAction::Pending->value
                    => 'Ticket Pending',

                    TicketProgressAction::Resume->value
                    => 'Resume Progress',

                    TicketProgressAction::Resolved->value
                    => 'Ticket Resolved',

                    TicketProgressAction::AcceptanceRejected->value
                    => 'Acceptance Rejected',

                    TicketProgressAction::AcceptanceApproved->value
                    => 'Ticket Accepted',

                    default
                    => 'Progress',
                };

                $metadata = [];

                if ($progress->ticketWaitingFor) {

                    $metadata['waiting_for'] = [

                        'id' => $progress->ticketWaitingFor->id,

                        'name' => $progress->ticketWaitingFor->name,

                    ];
                }

                return  $this->buildTimeline(

                    time: $progress->created_at,

                    action: $progress->action,

                    title: $title,

                    description: $progress->resolution_notes
                        ?? $progress->progress_notes,

                    user: $progress->user,

                    metadata: [
                        'waiting_for' => $metadata
                    ]

                );
            })

            ->values();
    }

    /**
     * Build closed timeline.
     */
    private function buildClosedTimeline(
        Ticket $ticket
    ): Collection {

        if (!$ticket->closed_at) {

            return collect();
        }

        return collect([

            $this->buildTimeline(

                time: $ticket->closed_at,

                action: TicketTimelineAction::Closed->value,

                title: 'Ticket Closed',

                description: $ticket->close_notes,

                user: $ticket->closer,

                metadata: [],

            ),

        ]);
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

                'reviewer',

                'closer',

                'applicationFeature.application',

                'category',

                'priority',

                'status',

                'activeAssignment.assignee',

                'activeAssignment.assigner',

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

    /**
     * Ensure ticket can be reviewed.
     */
    private function ensureReviewable(
        Ticket $ticket
    ): void {
        if (
            $ticket->status->code !==
            TicketStatusCode::Submitted->value
        ) {
            abort(
                422,
                'Only submitted ticket can be reviewed.'
            );
        }
    }

    private function resolveStatusId(
        TicketStatusCode|string $status
    ): int {
        $code = $status instanceof TicketStatusCode
            ? $status->value
            : $status;

        return TicketStatus::query()
            ->where('code', $code)
            ->value('id');
    }

    private function ensureAssignable(
        Ticket $ticket
    ): void {
        if (
            $ticket->status->code !==
            TicketStatusCode::Reviewed->value
        ) {
            abort(
                422,
                'Only reviewed ticket can be assigned.'
            );
        }
    }

    private function ensureReassignable(
        Ticket $ticket
    ): void {
        if (! in_array(

            $ticket->status->code,

            [

                TicketStatusCode::Assigned->value,

                TicketStatusCode::InProgress->value,

                TicketStatusCode::Pending->value,


            ]

        )) {

            abort(
                422,
                'Ticket cannot be reassigned.'
            );
        }
    }


    private function ensureAssignableUser(
        int $userId
    ): void {

        $user = User::with('roles')
            ->findOrFail($userId);

        $roleIds = TicketAssignableRole::query()
            ->pluck('role_id');

        if (! $user->roles()
            ->whereIn('id', $roleIds)
            ->exists()) {

            abort(
                422,
                'Selected user cannot receive ticket assignment.'
            );
        }
    }

    /**
     * Ensure ticket can be started.
     */
    private function ensureStartable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !==
            TicketStatusCode::Assigned->value
        ) {

            abort(
                422,
                'Only assigned ticket can be started.'
            );
        }
    }

    /**
     * Ensure ticket can be pending.
     */
    private function ensurePendingable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !==
            TicketStatusCode::InProgress->value
        ) {

            abort(
                422,
                'Only in progress ticket can be pending.'
            );
        }
    }

    /**
     * Ensure ticket can be resumed.
     */
    private function ensureResumable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !==
            TicketStatusCode::Pending->value
        ) {

            abort(
                422,
                'Only pending ticket can be resumed.'
            );
        }
    }

    /**
     * Ensure ticket can be resolved.
     */
    private function ensureResolvable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !==
            TicketStatusCode::InProgress->value
        ) {

            abort(
                422,
                'Only in progress ticket can be resolved.'
            );
        }
    }

    /**
     * Ensure ticket can be accepted.
     */
    private function ensureAcceptable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code !==
            TicketStatusCode::Resolved->value
        ) {

            abort(
                422,
                'Only resolved ticket can be accepted.'
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
