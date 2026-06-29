<?php

namespace App\Shared\Filters;

class ApplicationFilter extends BaseQueryFilter
{
    /**
     * Columns used by global search.
     */
    protected function searchable(): array
    {
        return [
            'name',
            'code',
            'description',
            'url',
        ];
    }
}