<?php

namespace Malyusha\Filterable;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * Filtered eloquent model class.
     *
     * @var string
     */
    protected static $model;

    /**
     * Filters that have already been used.
     *
     * @var array
     */
    protected static $filtered = [];

    /**
     * @param array $filters
     * @param null $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function apply(array $filters, $builder = null)
    {
        $builder = $builder ?: static::getModel()->newQuery();

        $builder = static::applyFiltersToBuilder($builder, $filters);

        return $builder;
    }

    /**
     * Applies all filters to builder instance;
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyFiltersToBuilder(Builder $builder, array $filters)
    {
        $filters = static::getNotEmpty($filters);
        foreach ($filters as $name => $value) {
            $decorator = static::createFilterDecorator($name);

            if(static::isValidDecorator($decorator) && !static::alreadyFiltered($name)) {
                $builder = $decorator::apply($builder, $value, static::$filtered);
                static::$filtered[$name] = true;
            }
        }

        return $builder;
    }

    /**
     * Checks whether filter have been already used.
     *
     * @param $name
     * @return mixed
     */
    protected static function alreadyFiltered($name)
    {
        return array_get(static::$filtered, $name, false);
    }

    /**
     * Creates full namespaced path to class of column.
     *
     * @param $filterName
     *
     * @return string
     */
    protected static function createFilterDecorator($filterName)
    {
        $class = get_called_class();
        $namespace = Str::replaceLast('\\' . class_basename($class), '', $class);

        return $namespace . '\\' . config('filterable.columns_folder') . '\\' . Str::studly($filterName);
    }

    /**
     * Check if decorator is a valid class.
     *
     * @param $decorator
     *
     * @return bool
     */
    protected static function isValidDecorator($decorator)
    {
        return class_exists($decorator) && is_subclass_of($decorator, ColumnInterface::class);
    }

    /**
     * Get only not empty values;
     *
     * @param $array
     *
     * @return array
     */
    protected static function getNotEmpty($array)
    {
        return array_filter($array, function ($item) {
            return (bool)$item;
        });
    }

    /**
     * Returns model object.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function getModel()
    {
       if(!static::$model) {
           throw new Exception('Model wasn\'t provided.');
       }

       return new static::$model;
    }
}