<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class ApplicationFeatureFilter extends BaseQueryFilter
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

        ];
    }

    /**
     * Apply custom filters.
     */
    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        if ($this->request->filled('application_id')) {

            $query->where(
                'application_id',
                $this->request->integer('application_id')
            );

        }

        return $query;
    }
}