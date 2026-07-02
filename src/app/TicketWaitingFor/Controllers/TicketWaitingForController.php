<?php

namespace App\TicketWaitingFor\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketWaitingFor;
use App\Shared\Responses\ApiResponse;
use App\TicketWaitingFor\Requests\StoreTicketWaitingForRequest;
use App\TicketWaitingFor\Requests\UpdateTicketWaitingForRequest;
use App\TicketWaitingFor\Resources\TicketWaitingForResource;
use App\TicketWaitingFor\Services\TicketWaitingForService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketWaitingForController extends Controller implements HasMiddleware
{
    public function __construct(
        private TicketWaitingForService $service
    ) {}

    public function index(Request $request)
    {
        $ticketWaitingFors = $this->service->index($request);

        return ApiResponse::paginated(

            TicketWaitingForResource::collection(
                $ticketWaitingFors
            ),

            'Ticket waiting fors retrieved successfully.'

        );
    }

    public function show(
        TicketWaitingFor $ticketWaitingFor
    ) {
        return ApiResponse::success(
            new TicketWaitingForResource(
                $this->service->show(
                    $ticketWaitingFor
                )
            ),
            'Ticket waiting for retrieved successfully.'
        );
    }

    public function store(
        StoreTicketWaitingForRequest $request,
    ) {
        $ticketWaitingFor = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new TicketWaitingForResource(
                $ticketWaitingFor
            ),
            'Ticket waiting for created successfully.',
            201
        );
    }

    public function update(
        UpdateTicketWaitingForRequest $request,
        TicketWaitingFor $ticketWaitingFor,
    ) {
        $ticketWaitingFor = $this->service->update(
            $ticketWaitingFor,
            $request->validated()
        );

        return ApiResponse::success(
            new TicketWaitingForResource(
                $ticketWaitingFor
            ),
            'Ticket waiting for updated successfully.'
        );
    }

    public function destroy(
        TicketWaitingFor $ticketWaitingFor,
    ) {
        $this->service->delete(
            $ticketWaitingFor
        );

        return ApiResponse::success(
            null,
            'Ticket waiting for deleted successfully.'
        );
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:ticket-waiting-for.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:ticket-waiting-for.create',
                only: ['store']
            ),

            new Middleware(
                'permission:ticket-waiting-for.update',
                only: ['update']
            ),

            new Middleware(
                'permission:ticket-waiting-for.delete',
                only: ['destroy']
            ),

        ];
    }
}