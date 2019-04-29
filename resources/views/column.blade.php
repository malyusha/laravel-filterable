<?php echo '<?php' . PHP_EOL;?>

namespace App\{{ $folder }}\{{ $filter }}\Columns;

use Malyusha\Filterable\ColumnInterface;
use Illuminate\Database\Eloquent\Builder;

class {{ $column }} implements ColumnInterface
{
    /**
     * Apply search value to the builder instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $value
     * @param array $filtered already filtered columns
     *
     * @return void
     */
    public static function apply(Builder $builder, $value, array $filtered = [])
    {
        //
    }
}