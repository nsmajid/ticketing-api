<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaRule extends Model
{
    protected $fillable = [

        'ticket_category_id',
        'ticket_priority_id',
        'response_hours',
        'resolution_hours',
        'is_active',

    ];

    public function category()
    {
        return $this->belongsTo(
            TicketCategory::class,
            'ticket_category_id'
        );
    }

    public function priority()
    {
        return $this->belongsTo(
            TicketPriority::class,
            'ticket_priority_id'
        );
    }
}
