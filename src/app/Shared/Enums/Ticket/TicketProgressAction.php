<?php
namespace App\Shared\Enums\Ticket;

enum TicketProgressAction:string
{
    case Start = 'START';

    case Pending = 'PENDING';

    case Resume = 'RESUME';

    case Resolved = 'RESOLVED';

    case AcceptanceRejected = 'ACCEPTANCE_REJECTED';

    case AcceptanceApproved = 'ACCEPTANCE_APPROVED';
}