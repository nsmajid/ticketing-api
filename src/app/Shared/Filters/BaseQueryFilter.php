<?php

namespace App\Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseQueryFilter
{
    public function __construct(
        protected Request $request
    ) {}

    public function apply(Builder $query): Builder
    {
        $this->search($query);
        $this->isActive($query);
        $this->sort($query);

        return $query;
    }

    /**
     * Override di child.
     */
    protected function searchable(): array
    {
        return [];
    }

    protected function search(Builder $query): void
    {
        if (
            !$this->request->filled('search')
            || empty($this->searchable())
        ) {
            return;
        }

        $keyword = trim(
            $this->request->string('search')
        );

        $query->where(function ($q) use ($keyword) {

            foreach ($this->searchable() as $column) {

                $q->orWhere(
                    $column,
                    'like',
                    "%{$keyword}%"
                );
            }
        });
    }

    protected function isActive(Builder $query): void
    {
        if (!$this->request->has('is_active')) {
            return;
        }

        $query->where(
            'is_active',
            filter_var(
                $this->request->is_active,
                FILTER_VALIDATE_BOOLEAN
            )
        );
    }

    protected function sort(Builder $query): void
    {
        $query->orderBy(
            $this->request->get('sort_by', 'id'),
            $this->request->get('sort_direction', 'desc')
        );
    }

    public function request(): Request
    {
        return $this->request;
    }

    public function perPage(): int
    {
        return max(
            1,
            min(
                $this->request->integer('per_page', 10),
                100
            )
        );
    }
}
