<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
