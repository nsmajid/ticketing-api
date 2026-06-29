<?php

namespace App\Shared\Filters;

class TicketCategoryFilter extends BaseQueryFilter
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