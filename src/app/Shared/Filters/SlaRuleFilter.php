<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class SlaRuleFilter extends BaseQueryFilter
{
    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        if ($this->request->filled('ticket_category_id')) {

            $query->where(
                'ticket_category_id',
                $this->request->ticket_category_id
            );

        }

        if ($this->request->filled('ticket_priority_id')) {

            $query->where(
                'ticket_priority_id',
                $this->request->ticket_priority_id
            );

        }

        return $query;
    }
}