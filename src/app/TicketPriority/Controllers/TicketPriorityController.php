<?php

namespace App\TicketPriority\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use App\TicketPriority\Requests\StoreTicketPriorityRequest;
use App\TicketPriority\Requests\UpdateTicketPriorityRequest;
use App\TicketPriority\Resources\TicketPriorityResource;
use App\TicketPriority\Services\TicketPriorityService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class TicketPriorityController extends Controller implements HasMiddleware
{
    public function index()
    {
        return TicketPriorityResource::collection(

            TicketPriority::query()
                ->orderBy('sort_order')
                ->paginate()

        );
    }

    public function show(
        TicketPriority $ticketPriority
    ) {
        return new TicketPriorityResource(
            $ticketPriority
        );
    }

    public function store(
        StoreTicketPriorityRequest $request,
        TicketPriorityService $service
    ) {
        return new TicketPriorityResource(

            $service->create(
                $request->validated()
            )

        );
    }

    public function update(
        UpdateTicketPriorityRequest $request,
        TicketPriority $ticketPriority,
        TicketPriorityService $service
    ) {
        return new TicketPriorityResource(

            $service->update(
                $ticketPriority,
                $request->validated()
            )

        );
    }

    public function destroy(
        TicketPriority $ticketPriority,
        TicketPriorityService $service
    ) {
        $service->delete(
            $ticketPriority
        );

       return response()->json([
            'success' => true,
            'message' => 'Ticket priority deleted successfully.',
        ]);
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
