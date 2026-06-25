<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
