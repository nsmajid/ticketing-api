<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketStatusFilter extends BaseQueryFilter
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

        if ($this->request->has('is_initial')) {

            $query->where(
                'is_initial',
                filter_var(
                    $this->request->is_initial,
                    FILTER_VALIDATE_BOOLEAN
                )
            );

        }

        return $query;
    }
}