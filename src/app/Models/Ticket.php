<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        /*
    |--------------------------------------------------------------------------
    | Ticket
    |--------------------------------------------------------------------------
    */

        'ticket_number',

        /*
    |--------------------------------------------------------------------------
    | Requester
    |--------------------------------------------------------------------------
    */

        'requester_id',

        /*
    |--------------------------------------------------------------------------
    | Reviewer
    |--------------------------------------------------------------------------
    */

        'reviewed_by',

        /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    */

        'application_feature_id',

        /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

        'ticket_category_id',

        'ticket_priority_id',

        'ticket_status_id',

        /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

        'contact_name',

        'contact_phone',

        /*
    |--------------------------------------------------------------------------
    | Ticket Detail
    |--------------------------------------------------------------------------
    */

        'subject',

        'description',

        /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    */

        'review_notes',

        /*
    |--------------------------------------------------------------------------
    | SLA
    |--------------------------------------------------------------------------
    */

        'submitted_at',

        'response_due_at',

        'resolution_due_at',

        'reviewed_at',

        'resolved_at',

        'closed_by',

        'closed_at',

        'close_notes',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        /*
    |--------------------------------------------------------------------------
    | Foreign Key
    |--------------------------------------------------------------------------
    */

        'requester_id' => 'integer',

        'reviewed_by' => 'integer',

        'application_feature_id' => 'integer',

        'ticket_category_id' => 'integer',

        'ticket_priority_id' => 'integer',

        'ticket_status_id' => 'integer',

        /*
    |--------------------------------------------------------------------------
    | Datetime
    |--------------------------------------------------------------------------
    */

        'submitted_at' => 'datetime',

        'response_due_at' => 'datetime',

        'resolution_due_at' => 'datetime',

        'reviewed_at' => 'datetime',

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

    /**
     * Reviewer.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TicketAssignment::class)
            ->orderBy('created_at');
    }

    /**
     * Active assignment.
     */
    public function activeAssignment(): HasOne
    {
        return $this->hasOne(
            TicketAssignment::class
        )->where('is_active', true);
    }

    /**
     * Ticket progresses.
     */
    public function progresses(): HasMany
    {
        return $this->hasMany(TicketProgress::class)
            ->orderBy('created_at');
    }
    /**
     * Ticket closer.
     */
    public function closer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by'
        );
    }
}
