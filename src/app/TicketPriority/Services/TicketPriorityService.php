<?php

namespace App\TicketPriority\Services;

use App\Models\TicketPriority;
use App\Shared\Services\BaseService;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketPriorityService extends BaseService
{

    public function index(Request $request): LengthAwarePaginator
    {
        return TicketPriority::query()
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            );
    }

    public function show(
        TicketPriority $priority
    ): TicketPriority {

        return TicketPriority::query()
            ->findOrFail($priority->id);
    }

    public function create(
        array $data
    ): TicketPriority {

        return $this->transaction(function () use ($data) {

            return TicketPriority::create($data);
        });
    }

    public function update(
        TicketPriority $priority,
        array $data
    ): TicketPriority {
        return $this->transaction(function () use (
            $priority,
            $data
        ) {

            $priority->update($data);

            return $priority->fresh();
        });
    }

    public function delete(
        TicketPriority $priority
    ): void {

        $this->deleteModel($priority);
    }
}
