<?php echo '<?php' . PHP_EOL;?>
<?php $modelClass = $model ? ' = ' . $model . '::class' : '';?>


namespace App\{{ $folder }}\{{ $filter }};

use Malyusha\Filterable\Filter as BaseFilter;

class {{ $className }} extends BaseFilter
{
    protected $model{{ $modelClass }};
}
