<?php

namespace Malyusha\Filterable;

interface FilterInterface
{
    /**
     * Applies filter from given array of values.
     *
     * @param array $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(array $filters): \Illuminate\Database\Eloquent\Builder;

    /**
     * Applies filter from query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     *
     * @return mixed
     */
    public function applyFromQuery(\Illuminate\Database\Eloquent\Builder $builder, array $filters);
}