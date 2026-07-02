<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketWaitingForFilter extends BaseQueryFilter
{
    protected function searchable(): array
    {
        return [

            'name',

            'code',

            'description',

        ];
    }

    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        if ($this->request->has('is_active')) {

            $query->where(
                'is_active',
                filter_var(
                    $this->request->is_active,
                    FILTER_VALIDATE_BOOLEAN
                )
            );

        }

        return $query;
    }
}