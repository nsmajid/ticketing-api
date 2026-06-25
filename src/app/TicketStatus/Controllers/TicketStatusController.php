<?php

namespace App\TicketStatus\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use App\TicketStatus\Requests\StoreTicketStatusRequest;
use App\TicketStatus\Resources\TicketStatusResource;
use App\TicketStatus\Services\TicketStatusService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketStatusController extends Controller implements HasMiddleware
{
    public function index()
    {
        return TicketStatusResource::collection(
            TicketStatus::query()
                ->orderBy('sort_order')
                ->paginate()
        );
    }

    public function show(
        TicketStatus $ticketStatus
    ) {
        return new TicketStatusResource(
            $ticketStatus
        );
    }

    public function store(
        StoreTicketStatusRequest $request,
        TicketStatusService $service
    ) {
        return new TicketStatusResource(
            $service->create(
                $request->validated()
            )
        );
    }

    public function update(
        StoreTicketStatusRequest $request,
        TicketStatus $ticketStatus,
        TicketStatusService $service
    ) {
        return new TicketStatusResource(
            $service->update(
                $ticketStatus,
                $request->validated()
            )
        );
    }

    public function destroy(
        TicketStatus $ticketStatus,
        TicketStatusService $service
    ) {
        $service->delete(
            $ticketStatus
        );

        return response()->json([
            'success' => true,
            'message' => 'Ticket status deleted successfully',
        ]);
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
