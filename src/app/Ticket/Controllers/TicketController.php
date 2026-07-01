<?php

namespace App\Ticket\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Shared\Responses\ApiResponse;
use App\Ticket\Requests\StoreTicketRequest;
use App\Ticket\Requests\UpdateTicketRequest;
use App\Ticket\Resources\TicketResource;
use App\Ticket\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketController extends Controller implements HasMiddleware
{
    public function __construct(
        private TicketService $service,
    ) {}

    /**
     * Display a listing.
     */
    public function index(Request $request)
    {
        $tickets = $this->service->index($request);

        return ApiResponse::paginated(

            TicketResource::collection(
                $tickets
            ),

            'Tickets retrieved successfully.'

        );
    }

    /**
     * Store a newly created ticket.
     */
    public function store(
        StoreTicketRequest $request,
    ) {

        $ticket = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(

            new TicketResource(
                $ticket
            ),

            'Ticket created successfully.',
            201

        );
    }

    /**
     * Display the specified ticket.
     */
    public function show(
        Ticket $ticket,
    ) {

        return ApiResponse::success(

            new TicketResource(

                $this->service->show(
                    $ticket
                )

            ),

            'Ticket retrieved successfully.'

        );
    }

    /**
     * Update the specified ticket.
     */
    public function update(
        UpdateTicketRequest $request,
        Ticket $ticket,
    ) {

        $ticket = $this->service->update(
            $ticket,
            $request->validated()
        );

        return ApiResponse::success(

            new TicketResource(
                $ticket
            ),

            'Ticket updated successfully.'

        );
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(
        Ticket $ticket,
    ) {

        $this->service->delete(
            $ticket
        );

        return ApiResponse::success(

            null,

            'Ticket deleted successfully.'

        );
    }

    /**
     * Submit ticket.
     */
    public function submit(
        Ticket $ticket,
    ) {
        $ticket = $this->service->submit(
            $ticket
        );

        return ApiResponse::success(

            new TicketResource(
                $ticket
            ),

            'Ticket submitted successfully.'

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
                'permission:ticket.create',
                only: [
                    'store',
                    'submit',
                ]
            ),

            new Middleware(
                'permission:ticket.update',
                only: [
                    'update',
                ]
            ),

            new Middleware(
                'permission:ticket.delete',
                only: [
                    'destroy',
                ]
            ),

        ];
    }
}
