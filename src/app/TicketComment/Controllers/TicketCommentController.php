<?php

namespace App\TicketComment\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Shared\Responses\ApiResponse;
use App\TicketComment\Requests\StoreTicketCommentRequest;
use App\TicketComment\Requests\UpdateTicketCommentRequest;
use App\TicketComment\Resources\TicketCommentResource;
use App\TicketComment\Services\TicketCommentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketCommentController extends Controller implements HasMiddleware
{
    public function __construct(
        private TicketCommentService $service,
    ) {}

    /**
     * Display listing.
     */
    public function index(
        Request $request,
        Ticket $ticket,
    ) {

        $comments = $this->service->index(
            $ticket,
            $request
        );

        return ApiResponse::paginated(

            TicketCommentResource::collection(
                $comments
            ),

            'Comments retrieved successfully.'

        );
    }

    /**
     * Store comment.
     */
    public function store(
        StoreTicketCommentRequest $request,
        Ticket $ticket,
    ) {

        $comment = $this->service->create(

            $ticket,

            $request->validated()

        );

        return ApiResponse::success(

            new TicketCommentResource(
                $comment
            ),

            'Comment created successfully.',

            201

        );
    }

    /**
     * Display comment.
     */
    public function show(
        Ticket $ticket,
        TicketComment $comment,
    ) {

       $this->ensureTicketComment(
    $ticket,
    $comment
);
        return ApiResponse::success(

            new TicketCommentResource(

                $this->service->show(
                    $comment
                )

            ),

            'Comment retrieved successfully.'

        );
    }

    /**
     * Update comment.
     */
    public function update(
        UpdateTicketCommentRequest $request,
        Ticket $ticket,
        TicketComment $comment,
    ) {

      $this->ensureTicketComment(
    $ticket,
    $comment
);

        $comment = $this->service->update(

            $comment,

            $request->validated()

        );

        return ApiResponse::success(

            new TicketCommentResource(
                $comment
            ),

            'Comment updated successfully.'

        );
    }

    /**
     * Delete comment.
     */
    public function destroy(
        Ticket $ticket,
        TicketComment $comment,
    ) {


        $this->ensureTicketComment(
            $ticket,
            $comment
        );
       

        $this->service->delete(
            $comment
        );

        return ApiResponse::success(

            null,

            'Comment deleted successfully.'

        );
    }

    private function ensureTicketComment(
        Ticket $ticket,
        TicketComment $comment
    ): void {
        abort_unless(
            $comment->ticket_id === $ticket->id,
            404
        );
    }

    /**
     * Controller Middleware.
     */
    public static function middleware(): array
    {
        return [

            new Middleware(

                'permission:ticket.view.all|ticket.view.assigned|ticket.view.own',

                only: [

                    'index',

                    'show',

                ]

            ),

            new Middleware(

                'permission:ticket.comment.create',

                only: [

                    'store',

                ]

            ),

            new Middleware(

                'permission:ticket.comment.update',

                only: [

                    'update',

                ]

            ),

            new Middleware(

                'permission:ticket.comment.delete',

                only: [

                    'destroy',

                ]

            ),

        ];
    }
}
