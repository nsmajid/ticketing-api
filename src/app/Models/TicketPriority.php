<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketPriority extends Model
{
    protected $fillable = [
        'name',
        'description',
        'response_hours',
        'resolution_hours',
        'color',
        'is_active',
        'sort_order',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(
            Ticket::class
        );
    }
}
