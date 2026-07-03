<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketProgress extends Model
{

    protected $table = 'ticket_progresses';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Relations
        |--------------------------------------------------------------------------
        */

        'ticket_id',

        'ticket_status_id',

        'action',

        'ticket_waiting_for_id',

        'user_id',

        /*
        |--------------------------------------------------------------------------
        | Progress
        |--------------------------------------------------------------------------
        */

        'progress_notes',

        'resolution_notes',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        'ticket_id' => 'integer',

        'ticket_status_id' => 'integer',

        'ticket_waiting_for_id' => 'integer',

        'user_id' => 'integer',

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
     * Ticket Status.
     */
    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(
            TicketStatus::class,
            'ticket_status_id'
        );
    }

    /**
     * Waiting For.
     */
    public function ticketWaitingFor(): BelongsTo
    {
        return $this->belongsTo(
            TicketWaitingFor::class,
            'ticket_waiting_for_id'
        );
    }

    /**
     * User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
