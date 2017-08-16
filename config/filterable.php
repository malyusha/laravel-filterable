<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filters folder
    |--------------------------------------------------------------------------
    |
    | Base folder for filters in app directory. All created filters will be
    | stored inside this folder. For instance, if you create filter via artisan
    | command "php artisan filterable:generate SomeFilter", "SomeFilter" will
    | be placed in "app/[Filters]/SomeFilter", directory.
    |
    */
    'folder' => 'Filters',

    /*
    |--------------------------------------------------------------------------
    | Columns folder
    |--------------------------------------------------------------------------
    |
    | This settings defines folder name for all columns inside filters. For
    | instance, you want to create filter via artisan command: "php artisan
    | filterable:generate SomeFilter --columns created_at,updated_at,title".
    | It will create "app/Filters/SomeFilter/[Columns]" folder where Columns
    | contains CreatedAt, UpdatedAt and Title classes.
    |
    */
    'columns_folder' => 'Columns',

    /*
    |--------------------------------------------------------------------------
    | Filter class name
    |--------------------------------------------------------------------------
    |
    | This setting defines filter class name by default for each filter.
    | For instance, you want to create filter via artisan command: "php artisan
    | filterable:generate SomeFilter". It will create "app/Filters/SomeFilter"
    | directory containing [Filter].php file. File name depends of this option.
    |
    */
    'filter_basename' => 'Filter'
];