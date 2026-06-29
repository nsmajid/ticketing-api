<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends BaseQueryFilter
{
    protected function searchable(): array
    {
        return [
            'name',
            'email',
        ];
    }

    public function apply(Builder $query): Builder
    {
        parent::apply($query);

        if ($this->request->filled('role')) {

            $query->role(
                $this->request->role
            );

        }

        return $query;
    }
}