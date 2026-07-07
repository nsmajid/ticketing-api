<?php

namespace App\Shared\Enums\Attachment;

enum AttachmentOwnerType: string
{
    case Ticket = 'ticket';

    case TicketComment = 'ticket_comment';

    case TicketProgress = 'ticket_progress';

    case TicketReview = 'ticket_review';

    case TicketClose = 'ticket_close';
}