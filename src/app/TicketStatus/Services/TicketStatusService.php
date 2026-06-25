<?php

namespace App\TicketStatus\Services;

use App\Models\TicketStatus;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Support\Facades\DB;

class TicketStatusService
{
    use EnvironmentProtection;

    /* Create */
    public function create(
        array $data
    ): TicketStatus {
        return DB::transaction(function () use ($data) {

            $this->resetBooleanFlag('is_initial');

            return TicketStatus::create($data);
        });
    }

    // UPDATE
    public function update(
        TicketStatus $ticketStatus,
        array $data
    ): TicketStatus {
        return DB::transaction(function () use ($ticketStatus, $data) {

            $this->resetBooleanFlag(
                'is_initial',
                $ticketStatus->id
            );

            $ticketStatus->update($data);

            return $ticketStatus->fresh();
        });
    }

    // DELETE
    public function delete(
        TicketStatus $status
    ): void {
        $this->ensureDevelopmentEnvironment();
        $status->delete();
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
