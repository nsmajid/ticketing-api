<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAssignment extends Model
{

    use HasFactory;

    protected $fillable = [

        'ticket_id',

        'assigned_to',

        'assigned_by',

        'assigned_at',

        'assignment_notes',

        'is_active',

    ];

    protected $casts = [

        'ticket_id' => 'integer',

        'assigned_to' => 'integer',

        'assigned_by' => 'integer',

        'assigned_at' => 'datetime',

        'is_active' => 'boolean',

    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            Ticket::class
        );
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_by'
        );
    }
}
