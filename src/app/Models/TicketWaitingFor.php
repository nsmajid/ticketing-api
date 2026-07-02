<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketWaitingFor extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'name',

        'code',

        'description',

        'is_active',

        'sort_order',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        'is_active' => 'boolean',

        'sort_order' => 'integer',

    ];

    /**
     * Ticket progresses waiting for this entity.
     */
    public function progresses(): HasMany
    {
        return $this->hasMany(
            TicketProgress::class,
            'ticket_waiting_for_id'
        );
    }
}
