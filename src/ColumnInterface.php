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
     * @return void
     */
    public static function apply(Builder $builder, $value, array $filtered = []);
}