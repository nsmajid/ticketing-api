<?php

namespace App\Models;

use App\Shared\Enums\Attachment\AttachmentOwnerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketComment extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'ticket_id',

        'user_id',

        'content',

    ];

    /**
     * Ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            Ticket::class
        );
    }

    /**
     * Author.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /**
     * Attachment usages.
     */
    public function attachmentUsages(): HasMany
    {
        return $this->hasMany(
            AttachmentUsage::class,
            'owner_id'
        )
            ->where(
                'owner_type',
                AttachmentOwnerType::TicketComment
            );
    }
}
