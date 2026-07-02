<?php

namespace App\Shared\Enums\Ticket;

enum TicketWaitingForCode: string
{
    case Client = 'CLIENT';

    case Vendor = 'VENDOR';

    case ThirdParty = 'THIRD_PARTY';

    case Infrastructure = 'INFRASTRUCTURE';

    case DBA = 'DBA';

    case DevOps = 'DEVOPS';

    case Management = 'MANAGEMENT';

    case Other = 'OTHER';
}