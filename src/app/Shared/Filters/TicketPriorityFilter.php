<?php

namespace App\Shared\Filters;

class TicketPriorityFilter extends BaseQueryFilter
{
    protected function searchable(): array
    {
        return [

            'name',

            'code',

            'description',

        ];
    }
}