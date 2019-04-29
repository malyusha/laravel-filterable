<?php

namespace Malyusha\Filterable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Malyusha\Filterable\Exceptions\ModelNotSet;

abstract class BaseFilter implements FilterInterface
{
    /**
     * Filtered eloquent model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Filters that have already been used.
     *
     * @var array
     */
    protected $filtered = [];

    /**
     * Applies filter from given array and optional query.
     * If query not passed filter will try to create query builder from model.
     *
     * @param array $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Malyusha\Filterable\Exceptions\ModelNotSet
     */
    public function apply(array $filters): Builder
    {
        return $this->applyFiltersToBuilder($this->getModel()->newQuery(), $filters);
    }

    /**
     * Applies filters from query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFromQuery(Builder $builder, array $filters): Builder
    {
        return $this->applyFiltersToBuilder($builder, $filters);
    }

    /**
     * Returns array of columns, that have been already filtered.
     *
     * @return array
     */
    public function getAlreadyFiltered(): array
    {
        return $this->filtered;
    }

    /**
     * Applies all filters to builder instance;
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFiltersToBuilder(Builder $builder, array $filters): Builder
    {
        $allowed = $this->getAllowedFilters();
        $filters = array_intersect_key($this->getFiltersToApply($filters), $allowed);

        foreach ($filters as $name => $value) {
            // Here we're totally sure that filter with `name` exists inside allowed, because we used
            // array key intersection
            $columnFilter = $allowed[$name];
            if (static::isValidColumn($columnFilter) && ! $this->alreadyFiltered($name)) {
                $columnFilter::apply($builder, $value, $this->filtered);
                $this->filtered[$name] = true;
            }
        }

        return $builder;
    }

    /**
     * Checks whether filter have been already used.
     *
     * @param $name
     *
     * @return bool
     */
    protected function alreadyFiltered($name): bool
    {
        return Arr::has($this->filtered, $name);
    }

    /**
     * Check if column is a valid class.
     *
     * @param $column
     *
     * @return bool
     */
    protected static function isValidColumn($column): bool
    {
        return class_exists($column) && is_subclass_of($column, ColumnInterface::class);
    }

    /**
     * Applies array_filter to values of given filters.
     *
     * @param array $values Array of values that need to be filtered.
     *
     * @return array
     */
    protected function getFiltersToApply(array $values): array
    {
        return array_filter($values, function ($item) {
            return (bool) $item;
        });
    }

    /**
     * Returns model object.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Malyusha\Filterable\Exceptions\ModelNotSet
     */
    public function getModel(): \Illuminate\Database\Eloquent\Model
    {
        if (! $this->model) {
            throw new ModelNotSet(static::class);
        }

        return new $this->model;
    }

    /**
     * Returns allowed filters assoc array, where key represents name of column, value is the filter class.
     *
     * @return array
     */
    abstract public function getAllowedFilters(): array;
}