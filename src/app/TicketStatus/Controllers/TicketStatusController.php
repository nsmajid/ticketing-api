<?php

namespace App\TicketStatus\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use App\Shared\Responses\ApiResponse;
use App\TicketStatus\Requests\StoreTicketStatusRequest;
use App\TicketStatus\Resources\TicketStatusResource;
use App\TicketStatus\Services\TicketStatusService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketStatusController extends Controller implements HasMiddleware
{
    public function __construct(
        private TicketStatusService $service
    ) {}

    public function index(Request $request)
    {
        $statuses = $this->service->index($request);

        return ApiResponse::paginated(

            TicketStatusResource::collection(
                $statuses
            ),

            'Ticket statuses retrieved successfully.'

        );
    }

    public function show(
        TicketStatus $ticketStatus
    ) {
        return ApiResponse::success(
            new TicketStatusResource(
                $this->service->show(
                    $ticketStatus
                )
            ),
            'Ticket status retrieved successfully.'
        );
    }

    public function store(
        StoreTicketStatusRequest $request,
    ) {
        $ticketStatus = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new TicketStatusResource($ticketStatus),
            'Ticket status created successfully.',
            201
        );
    }

    public function update(
        StoreTicketStatusRequest $request,
        TicketStatus $ticketStatus,
    ) {
        $ticketStatus = $this->service->update(
            $ticketStatus,
            $request->validated()
        );

        return ApiResponse::success(
            new TicketStatusResource($ticketStatus),
            'Ticket status updated successfully.'
        );
    }

    public function destroy(
        TicketStatus $ticketStatus,
    ) {
        $this->service->delete($ticketStatus);

        return ApiResponse::success(
            null,
            'Ticket status deleted successfully.'
        );
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:ticket-status.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:ticket-status.create',
                only: ['store']
            ),

            new Middleware(
                'permission:ticket-status.update',
                only: ['update']
            ),

            new Middleware(
                'permission:ticket-status.delete',
                only: ['destroy']
            ),
        ];
    }
}
