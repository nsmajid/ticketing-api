<?php

namespace App\TicketPriority\Services;
use App\Models\TicketPriority;
use App\Shared\Traits\EnvironmentProtection;

class TicketPriorityService
{
    use EnvironmentProtection;
   
    public function create(
        array $data
    ): TicketPriority {

        return TicketPriority::create(
            $data
        );
    }

    public function update(
        TicketPriority $priority,
        array $data
    ): TicketPriority {

        $priority->update(
            $data
        );

        return $priority->fresh();
    }

    public function delete(
        TicketPriority $priority
    ): void {
        
        $this->ensureDevelopmentEnvironment();
        

        $priority->delete();
    }
}
