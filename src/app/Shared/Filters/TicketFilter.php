<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketFilter extends BaseQueryFilter
{
    protected function searchable(): array
    {
        return [

            'ticket_number',

            'title',

            'description',

        ];
    }

    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        if ($this->request->filled('status')) {

            $query->where(
                'ticket_status_id',
                $this->request->status
            );

        }

        if ($this->request->filled('priority')) {

            $query->where(
                'ticket_priority_id',
                $this->request->priority
            );

        }

        if ($this->request->filled('category')) {

            $query->where(
                'ticket_category_id',
                $this->request->category
            );

        }

        if ($this->request->filled('assignee')) {

            $query->where(
                'assignee_id',
                $this->request->assignee
            );

        }

        return $query;
    }
}