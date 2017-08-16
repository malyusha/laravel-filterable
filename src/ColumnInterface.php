<?php


namespace Malyusha\Filterable;

use Illuminate\Database\Eloquent\Builder;

interface ColumnInterface
{
    /**
     * Apply search value to the builder instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $value
     * @param array $filtered Already filtered classes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function apply(Builder $builder, $value, array $filtered = []);
}