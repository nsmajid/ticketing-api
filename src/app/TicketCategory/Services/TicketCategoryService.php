<?php

namespace App\TicketCategory\Services;

use App\Models\TicketCategory;
use App\Shared\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketCategoryService extends BaseService
{
    public function index(Request $request): LengthAwarePaginator
    {
        return TicketCategory::query()
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            );
    }

    public function show(
        TicketCategory $category
    ): TicketCategory {

        return TicketCategory::query()
            ->findOrFail($category->id);
    }

    public function create(
        array $data
    ): TicketCategory {

        return $this->transaction(function () use ($data) {

            return TicketCategory::create($data);
        });
    }

    public function update(
        TicketCategory $category,
        array $data
    ): TicketCategory {

        return $this->transaction(function () use (
            $category,
            $data
        ) {

            $category->update($data);

            return $category->fresh();
        });
    }

    public function delete(
        TicketCategory $category
    ): void {

        $this->deleteModel($category);
    }
}
