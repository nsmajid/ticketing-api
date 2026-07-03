<?php

namespace App\Shared\Enums\Ticket;

enum TicketTimelineAction: string
{
    case Created = 'CREATED';

    case Submitted = 'SUBMITTED';

    case Reviewed = 'REVIEWED';

    case Rejected = 'REJECTED';

    case Assigned = 'ASSIGNED';

    case Reassigned = 'REASSIGNED';

    case Closed = 'CLOSED';
}