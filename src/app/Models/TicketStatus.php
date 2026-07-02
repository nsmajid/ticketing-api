<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    protected $fillable = [

        'name',
        'code',
        'description',
        'color',
        'icon',
        'is_initial',
        'is_closed',
        'is_resolved',
        'is_active',
        'sort_order',

    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(
            Ticket::class
        );
    }

    /**
 * Ticket progresses.
 */
public function progresses(): HasMany
{
    return $this->hasMany(
        TicketProgress::class
    );
}
}
