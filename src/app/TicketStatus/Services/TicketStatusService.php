<?php

namespace App\TicketStatus\Services;

use App\Models\TicketStatus;
use App\Shared\Services\BaseService;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TicketStatusService extends BaseService
{
    public function index(Request $request): LengthAwarePaginator
    {
        return TicketStatus::query()
            ->latest()
            ->paginate(
                $request->integer('per_page', 10)
            );
    }

    public function show(
        TicketStatus $status
    ): TicketStatus {
        return TicketStatus::query()
            ->findOrFail($status->id);
    }
    /* Create */
    public function create(
        array $data
    ): TicketStatus {

        return $this->transaction(function () use ($data) {

            if (!empty($data['is_initial'])) {

                $this->resetInitialStatus();
            }

            return TicketStatus::create($data);
        });
    }

    // UPDATE
    public function update(
        TicketStatus $ticketStatus,
        array $data
    ): TicketStatus {
        return $this->transaction(function () use (
            $ticketStatus,
            $data
        ) {

            if (!empty($data['is_initial'])) {

                $this->resetInitialStatus(
                    $ticketStatus->id
                );
            }

            $ticketStatus->update($data);

            return $ticketStatus->fresh();
        });
    }

    // DELETE
    public function delete(
        TicketStatus $ticketStatus
    ): void {
        $this->deleteModel($ticketStatus);
    }

    private function resetInitialStatus(
        ?int $ignoreId = null
    ): void {

        TicketStatus::query()
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->update([
                'is_initial' => false,
            ]);
    }

    private function resetBooleanFlag(
        string $column,
        ?int $ignoreId = null
    ): void {
        TicketStatus::query()
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->update([
                $column => false,
            ]);
    }
}
