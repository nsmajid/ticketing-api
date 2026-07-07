<?php

namespace App\TicketComment\Services;

use App\Attachment\Services\AttachmentParserService;
use App\Attachment\Services\AttachmentQueryService;
use App\Attachment\Services\AttachmentUsageService;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Shared\Enums\Attachment\AttachmentOwnerType;
use App\Shared\Enums\System\Role;
use App\Shared\Enums\Ticket\TicketStatusCode;
use App\Shared\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketCommentService extends BaseService
{
    public function __construct(
        private AttachmentParserService $attachmentParser,
        private AttachmentUsageService $attachmentUsage,
        private AttachmentQueryService $attachmentQuery,

    ) {}

    /**
     * Display listing.
     */
    public function index(
        Ticket $ticket,
        Request $request
    ): LengthAwarePaginator {

        return $this->baseQuery()

            ->where(
                'ticket_id',
                $ticket->id
            )

            ->paginate(
                $request->integer('per_page', 20)
            );
    }

    /**
     * Show detail.
     */
    public function show(
        TicketComment $comment
    ): TicketComment {

        return $this->baseQuery()
            ->findOrFail($comment->id);
    }

    /**
     * Store comment.
     */
    public function create(
        Ticket $ticket,
        array $data
    ): TicketComment {

        $this->ensureCreatable($ticket);

        return $this->transaction(function () use (
            $ticket,
            $data
        ) {

            $comment = TicketComment::create([

                'ticket_id' => $ticket->id,

                'user_id' => auth()->id(),

                'content' => trim(
                    $data['content']
                ),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Attachment Usage
        |--------------------------------------------------------------------------
        */

            $attachmentIds = $this->attachmentParser
                ->attachmentIds(
                    $comment->content
                );

            $this->attachmentUsage->sync(

                AttachmentOwnerType::TicketComment,

                $comment->id,

                $attachmentIds

            );

            return $this->show($comment);
        });
    }

    /**
     * Update comment.
     */
    public function update(
        TicketComment $comment,
        array $data
    ): TicketComment {

        $this->ensureEditable($comment);

        return $this->transaction(function () use (
            $comment,
            $data
        ) {

            $comment->update([

                'content' => trim(
                    $data['content']
                ),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Attachment Usage
        |--------------------------------------------------------------------------
        */

            $attachmentIds = $this->attachmentParser
                ->attachmentIds(
                    $comment->content
                );

            $this->attachmentUsage->sync(

                AttachmentOwnerType::TicketComment,

                $comment->id,

                $attachmentIds

            );

            return $this->show($comment);
        });
    }

    /**
     * Delete comment.
     */
    public function delete(
        TicketComment $comment
    ): void {

        $this->ensureDeletable($comment);

        $this->transaction(function () use (
            $comment
        ) {

            /*
        |--------------------------------------------------------------------------
        | Delete Attachment Usage
        |--------------------------------------------------------------------------
        */

            $this->attachmentUsage
                ->deleteOwner(

                    AttachmentOwnerType::TicketComment,

                    $comment->id

                );

            $comment->delete();
        });
    }

    /**
     * Base query.
     */
    private function baseQuery(): Builder
    {
        return TicketComment::query()

            ->with([
                'user',
                'ticket',
                'attachmentUsages.attachment',
            ])

            ->orderBy('id');
    }
    /**
     * Ensure ticket can create comment.
     */
    private function ensureCreatable(
        Ticket $ticket
    ): void {

        if (
            $ticket->status->code ===
            TicketStatusCode::Closed->value
        ) {

            abort(
                422,
                'Ticket has been closed.'
            );
        }
        if (
            $ticket->status->code ===
            TicketStatusCode::Draft->value
        ) {

            abort(
                422,
                'Ticket is in draft.'
            );
        }
    }

    /**
     * Ensure comment can be edited.
     */
    private function ensureEditable(
        TicketComment $comment
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Author / Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            !$this->isSuperAdmin()
            && $comment->user_id !== auth()->id()
        ) {

            abort(
                403,
                'You are not allowed to edit this comment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ticket Status
        |--------------------------------------------------------------------------
        */

        if (
            $comment->ticket->status->code ===
            TicketStatusCode::Closed->value
        ) {

            abort(
                422,
                'Ticket has been closed.'
            );
        }


        if (
            $comment->ticket->status->code ===
            TicketStatusCode::Draft->value
        ) {

            abort(
                422,
                'Ticket is in draft.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Last Comment Only
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasNextComment($comment)
        ) {

            abort(
                422,
                'Only the latest comment can be edited.'
            );
        }
    }

    /**
     * Ensure comment can be deleted.
     */
    private function ensureDeletable(
        TicketComment $comment
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Author / Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            !$this->isSuperAdmin()
            && $comment->user_id !== auth()->id()
        ) {

            abort(
                403,
                'You are not allowed to delete this comment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ticket Status
        |--------------------------------------------------------------------------
        */

        if (
            $comment->ticket->status->code ===
            TicketStatusCode::Closed->value
        ) {

            abort(
                422,
                'Ticket has been closed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Last Comment Only
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasNextComment($comment)
        ) {

            abort(
                422,
                'Only the latest comment can be deleted.'
            );
        }
    }

    /**
     * Determine whether comment has newer comment.
     */
    private function hasNextComment(
        TicketComment $comment
    ): bool {

        return TicketComment::query()

            ->where(
                'ticket_id',
                $comment->ticket_id
            )

            ->where(
                'id',
                '>',
                $comment->id
            )

            ->exists();
    }

    /**
     * Determine current user is Super Admin.
     */
    private function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole(
            Role::SuperAdmin->value
        );
    }
}
