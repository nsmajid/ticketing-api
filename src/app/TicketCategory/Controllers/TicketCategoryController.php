<?php

namespace App\TicketCategory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\Shared\Responses\ApiResponse;
use App\TicketCategory\Requests\StoreTicketCategoryRequest;
use App\TicketCategory\Requests\UpdateTicketCategoryRequest;
use App\TicketCategory\Resources\TicketCategoryResource;
use App\TicketCategory\Services\TicketCategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TicketCategoryController extends Controller implements HasMiddleware
{

    public function __construct(
        protected TicketCategoryService $service
    ) {}
    public function index(Request $request)
    {
        $categories = $this->service->index($request);

        return ApiResponse::paginated(

            TicketCategoryResource::collection(
                $categories
            ),

            'Ticket categories retrieved successfully.'

        );
    }

    public function show(
        TicketCategory $ticketCategory
    ) {
        return ApiResponse::success(
            new TicketCategoryResource(
                $this->service->show(
                    $ticketCategory
                )
            ),
            'Ticket category retrieved successfully.'
        );
    }

    public function store(
        StoreTicketCategoryRequest $request,
    ) {

        $ticketCategory = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            new TicketCategoryResource($ticketCategory),
            'Ticket category created successfully.',
            201
        );
    }

    public function update(
        UpdateTicketCategoryRequest $request,
        TicketCategory $ticketCategory,
    ) {

        $ticketCategory = $this->service->update(
            $ticketCategory,
            $request->validated()
        );

        return ApiResponse::success(
            new TicketCategoryResource($ticketCategory),
            'Ticket category updated successfully.'
        );
    }

    public function destroy(
        TicketCategory $ticketCategory,
    ) {

        $this->service->delete($ticketCategory);

        return ApiResponse::success(
            null,
            'Ticket category deleted successfully.'
        );
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
