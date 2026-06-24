<?php

namespace App\TicketCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\TicketCategory\Requests\StoreTicketCategoryRequest;
use App\TicketCategory\Requests\UpdateTicketCategoryRequest;
use App\TicketCategory\Resources\TicketCategoryResource;
use App\TicketCategory\Services\TicketCategoryService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketCategoryController extends Controller implements HasMiddleware
{
    public function index()
    {
        return TicketCategoryResource::collection(

            TicketCategory::query()
                ->orderBy('sort_order')
                ->paginate()

        );
    }

    public function show(
        TicketCategory $ticketCategory
    ) {
        return new TicketCategoryResource(
            $ticketCategory
        );
    }

    public function store(
        StoreTicketCategoryRequest $request,
        TicketCategoryService $service
    ) {
        return new TicketCategoryResource(

            $service->create(
                $request->validated()
            )

        );
    }

    public function update(
        UpdateTicketCategoryRequest $request,
        TicketCategory $ticketCategory,
        TicketCategoryService $service
    ) {
        return new TicketCategoryResource(

            $service->update(
                $ticketCategory,
                $request->validated()
            )

        );
    }

    public function destroy(
        TicketCategory $ticketCategory,
        TicketCategoryService $service
    ) {
        $service->delete(
            $ticketCategory
        );

        return response()->json([
            'success' => true,
            'message' => 'Ticket category deleted successfully',
        ]);
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:ticket-category.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:ticket-category.create',
                only: ['store']
            ),

            new Middleware(
                'permission:ticket-category.update',
                only: ['update']
            ),

            new Middleware(
                'permission:ticket-category.delete',
                only: ['destroy']
            ),

        ];
    }
}
