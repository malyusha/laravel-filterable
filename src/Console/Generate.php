<?php

namespace Malyusha\Filterable\Console;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Malyusha\Filterable\Exceptions\DirectoryCreateException;
use Malyusha\Filterable\Exceptions\DirectoryExistsException;
use Malyusha\Filterable\Exceptions\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filterable:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates filter.';

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws \Exception
     */
    public function handle()
    {
        // Get filter directory inside app folder
        $directory = app_path(config('filterable.folder') . DIRECTORY_SEPARATOR . $this->getFilterName());
        $columnsDirectory = $directory . DIRECTORY_SEPARATOR . config('filterable.columns_folder');

        try {
            $this->createDirectory($directory);
            $this->createFilter($directory);
            $this->createDirectory($columnsDirectory);
            if($this->option('columns')) {
                $this->createColumns($columnsDirectory);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        $this->info('Filters successfully generated!');

        return true;
    }

    /**
     * Creates filter class.
     *
     * @param string $directory
     * @throws \Malyusha\Filterable\Exceptions\Exception
     */
    protected function createFilter(string $directory)
    {
        $file = $directory . DIRECTORY_SEPARATOR . $this->getClassName() . '.php';

        if(!File::put($file, $this->generateViewStub('filter'))) {
            throw new Exception('Failed creating filter ' . $file);
        }
    }


    /**
     * Creates directory if it doesn't exist.
     *
     * @param string $directory
     * @return bool
     * @throws \Malyusha\Filterable\Exceptions\Exception
     */
    protected function createDirectory(string $directory)
    {
        if(File::isDirectory($directory)) {
            // First, check if the directory exists
            throw new DirectoryExistsException($directory);
        }

        if(!File::makeDirectory($directory)) {
            // Then we try to create directory
            throw new DirectoryCreateException($directory);
        }

        return true;
    }

    /**
     * Creates column classes from input option.
     *
     * @param $directory
     * @return bool
     * @throws \Malyusha\Filterable\Exceptions\Exception
     */
    protected function createColumns($directory)
    {
        foreach (explode(',', $this->option('columns')) as $column) {
            $column = Str::studly(trim($column));
            $file = $directory . DIRECTORY_SEPARATOR . $column . '.php';

            if(!File::put($file, $this->generateViewStub('column', compact('column')))) {
                throw new Exception('Failed creating column ' . $file);
            }
        }

        return true;
    }

    /**
     * Renders given view.
     *
     * @param string $view
     *
     * @param array $data
     * @return $this
     */
    public function generateViewStub(string $view, array $data = [])
    {
        $data = array_merge($this->getViewData(), $data);

        return view()->make('filterable::' . $view)->with($data)->render();
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
       return [
           ['filter', InputArgument::REQUIRED, 'Filter class name']
       ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['name', null, InputOption::VALUE_OPTIONAL, 'Base name of filtering class'],
            ['columns', 'c', InputOption::VALUE_REQUIRED, 'The columns to create'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Model for filter']
        ];
    }

    /**
     * Returns data for stub views.
     *
     * @return array
     * @throws \Malyusha\Filterable\Exceptions\Exception
     */
    protected function getViewData()
    {
        return [
            'folder' => config('filterable.folder'),
            'className' => $this->getClassName(),
            'filter' => $this->getFilterName(),
            'model' => $this->getOptionalModel()
        ];
    }

    /**
     * Returns normalized filter name.
     *
     * @return string
     */
    public function getFilterName()
    {
        return Str::studly($this->argument('filter'));
    }

    /**
     * Returns optional model class for filter.
     *
     * @return array|string
     * @throws \Malyusha\Filterable\Exceptions\Exception
     */
    public function getOptionalModel()
    {
        if(!$model = $this->option('model')) {
            return null;
        }

        try {
            $namespace = str_replace('\\', '', app()->getNamespace());
        } catch (\RuntimeException $e) {
            throw new Exception($e->getMessage());
        }

        $model = str_replace('/', '\\', $model);

        return Str::startsWith($namespace, $model) ? $model : '\\' . $namespace . '\\' . $model;
    }

    /**
     * Returns base class name of filter.
     *
     * @return mixed
     */
    public function getClassName()
    {
        return $this->option('name') ?: config('filterable.filter_basename');
    }
}