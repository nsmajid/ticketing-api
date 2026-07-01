<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'ticket_number',

        'requester_id',

        'application_feature_id',

        'ticket_category_id',

        'ticket_priority_id',

        'ticket_status_id',

        'contact_name',

        'contact_phone',

        'subject',

        'description',

        'response_due_at',

        'resolution_due_at',

        'submitted_at',

        'resolved_at',

        'closed_at',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        'requester_id' => 'integer',

        'application_feature_id' => 'integer',

        'ticket_category_id' => 'integer',

        'ticket_priority_id' => 'integer',

        'ticket_status_id' => 'integer',

        'submitted_at' => 'datetime',

        'response_due_at' => 'datetime',

        'resolution_due_at' => 'datetime',

        'resolved_at' => 'datetime',

        'closed_at' => 'datetime',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Requester.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requester_id'
        );
    }

    /**
     * Application Feature.
     */
    public function applicationFeature(): BelongsTo
    {
        return $this->belongsTo(
            ApplicationFeature::class
        );
    }

    /**
     * Ticket Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            TicketCategory::class,
            'ticket_category_id'
        );
    }

    /**
     * Ticket Priority.
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(
            TicketPriority::class,
            'ticket_priority_id'
        );
    }

    /**
     * Ticket Status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(
            TicketStatus::class,
            'ticket_status_id'
        );
    }
}
