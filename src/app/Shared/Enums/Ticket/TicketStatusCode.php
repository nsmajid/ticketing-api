<?php

namespace App\Shared\Enums\Ticket;

use App\Shared\Enums\Concerns\HasValues;


enum TicketStatusCode: string
{
    use HasValues;

    // case Open = 'OPEN';
    case Assigned = 'ASSIGNED';
    case InProgress = 'IN_PROGRESS';
    // case WaitingReview = 'WAITING_REVIEW';
    case Closed = 'CLOSED';
    case Rejected = 'REJECTED';
    case Draft = 'DRAFT';
    case Submitted = 'SUBMITTED';
    case Reviewed = 'REVIEWED';
    // case PendingClient = 'PENDING_CLIENT';
    // case PendingVendor = 'PENDING_VENDOR';
    case Pending = 'PENDING';
    case Resolved = 'RESOLVED';
    case Cancelled = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Reviewed => 'Reviewed',
            self::Assigned => 'Assigned',
            self::InProgress => 'In Progress',
            // self::PendingClient => 'Pending Client',
            // self::PendingVendor => 'Pending Vendor',
            self::Pending => 'Pending',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
        };
    }
}