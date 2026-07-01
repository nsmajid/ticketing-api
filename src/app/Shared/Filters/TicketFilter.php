<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketFilter extends BaseQueryFilter
{
    /**
     * Columns used by global search.
     */
    protected function searchable(): array
    {
        return [

            'ticket_number',

            'subject',

            'description',

            'contact_name',

            'contact_phone',

        ];
    }

    /**
     * Apply custom filters.
     */
    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        /*
        |--------------------------------------------------------------------------
        | Requester
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('requester_id')) {

            $query->where(
                'requester_id',
                $this->request->integer('requester_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Application Feature
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('application_feature_id')) {

            $query->where(
                'application_feature_id',
                $this->request->integer('application_feature_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Application
        |--------------------------------------------------------------------------
        */
        if ($this->request->filled('application_id')) {

            $query->whereHas('applicationFeature', function (Builder $query) {

                $query->where(
                    'application_id',
                    $this->request->integer('application_id')
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('ticket_category_id')) {

            $query->where(
                'ticket_category_id',
                $this->request->integer('ticket_category_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('ticket_priority_id')) {

            $query->where(
                'ticket_priority_id',
                $this->request->integer('ticket_priority_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('ticket_status_id')) {

            $query->where(
                'ticket_status_id',
                $this->request->integer('ticket_status_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($this->request->filled('submitted_from')) {

            $query->whereDate(
                'submitted_at',
                '>=',
                $this->request->submitted_from
            );
        }

        if ($this->request->filled('submitted_to')) {

            $query->whereDate(
                'submitted_at',
                '<=',
                $this->request->submitted_to
            );
        }

        if ($this->request->boolean('my_ticket')) {

            $query->where(
                'requester_id',
                auth()->id()
            );
        }
        
        return $query;
    }
}
