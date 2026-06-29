<?php

namespace App\TicketPriority\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use App\Shared\Responses\ApiResponse;
use App\TicketPriority\Requests\StoreTicketPriorityRequest;
use App\TicketPriority\Requests\UpdateTicketPriorityRequest;
use App\TicketPriority\Resources\TicketPriorityResource;
use App\TicketPriority\Services\TicketPriorityService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class TicketPriorityController extends Controller implements HasMiddleware
{
    public function __construct(
        private TicketPriorityService $service
    ) {}

    public function index(Request $request)
    {
        $priorities = $this->service->index($request);

        return ApiResponse::paginated(

            TicketPriorityResource::collection(
                $priorities
            ),

            'Ticket priorities retrieved successfully.'

        );
    }

    public function show(
        TicketPriority $ticketPriority
    ) {
        return ApiResponse::success(
            new TicketPriorityResource(
                $this->service->show(
                    $ticketPriority
                )
            ),
            'Ticket priority retrieved successfully.'

        );
    }

    public function store(
        StoreTicketPriorityRequest $request,
    ) {

        $ticketPriority = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new TicketPriorityResource($ticketPriority),
            'Ticket priority created successfully.',
            201
        );
    }

    public function update(
        UpdateTicketPriorityRequest $request,
        TicketPriority $ticketPriority,
    ) {

        $ticketPriority = $this->service->update(
            $ticketPriority,
            $request->validated()
        );

        return ApiResponse::success(
            new TicketPriorityResource($ticketPriority),
            'Ticket priority updated successfully.'
        );
    }

    public function destroy(
        TicketPriority $ticketPriority,
    ) {

        $this->service->delete($ticketPriority);

        return ApiResponse::success(
            null,
            'Ticket priority deleted successfully.'
        );
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:ticket-priority.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:ticket-priority.create',
                only: ['store']
            ),

            new Middleware(
                'permission:ticket-priority.update',
                only: ['update']
            ),

            new Middleware(
                'permission:ticket-priority.delete',
                only: ['destroy']
            ),
        ];
    }
}
