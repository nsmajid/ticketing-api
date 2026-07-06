<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}