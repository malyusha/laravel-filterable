<?php

namespace Malyusha\Filterable;

use Illuminate\Support\ServiceProvider;
use Malyusha\Filterable\Console\Generate;

class FilterableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/filterable.php' => config_path('filterable.php')
        ], 'config');

        $this->app->view->addNamespace('filterable', __DIR__ . '/../resources/views');

        $this->commands(Generate::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filterable.php', 'filterable');
    }
}