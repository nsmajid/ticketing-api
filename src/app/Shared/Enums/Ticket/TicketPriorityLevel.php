<?php

namespace App\Shared\Enums\Ticket;

use App\Shared\Enums\Concerns\HasValues;



enum TicketPriorityLevel: string
{
    use HasValues;

    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Critical => 'Critical',
        };
    }
}