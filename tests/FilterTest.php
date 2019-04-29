<?php

namespace Malyusha\Filterable\Tests;

class FilterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \Malyusha\Filterable\BaseFilter::applyFromQuery()
     */
    public function test_it_applies_filters()
    {
        $filter = new TestBaseFilter();
        $filter->applyFromQuery(new_mock_builder(), ['fake' => 'something', 'name' => 'Test']);

        $this->assertEquals(['name' => true], $filter->getAlreadyFiltered());
    }

    public function test_it_applies_filter_automatically()
    {
        $filter = new TestBaseFilterWithModel();

        $filter->apply(['fake' => 'something', 'name' => 'Test']);
        $this->assertEquals(['name' => true], $filter->getAlreadyFiltered());
    }

    /**
     * @covers \Malyusha\Filterable\BaseFilter::getModel()
     *
     * @throws \Malyusha\Filterable\Exceptions\ModelNotSet
     */
    public function test_it_throws_exception_when_model_not_set()
    {
        $filter = new TestBaseFilter();
        $this->expectException(\Malyusha\Filterable\Exceptions\ModelNotSet::class);
        $filter->apply(['test' => 'value']);
    }
}

class TestModel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'testing';

    public function newQuery()
    {
        return new_mock_builder();
    }
}

class TestBaseFilterWithModel extends \Malyusha\Filterable\BaseFilter
{
    protected $model = TestModel::class;

    /**
     * Returns allowed filters assoc array, where key represents name of column, value is the filter class.
     *
     * @return array
     */
    public function getAllowedFilters(): array
    {
        return [
            'name' => Name::class,
        ];
    }
}

class TestBaseFilter extends \Malyusha\Filterable\BaseFilter
{
    /**
     * Returns allowed filters assoc array, where key represents name of column, value is the filter class.
     *
     * @return array
     */
    public function getAllowedFilters(): array
    {
        return [
            'name' => Name::class,
        ];
    }
}

class Name implements \Malyusha\Filterable\ColumnInterface
{
    public static function apply(\Illuminate\Database\Eloquent\Builder $builder, $value, array $filtered = [])
    {
        $builder->where('name', $value);
    }
}

function new_mock_builder()
{
    $connection = \Mockery::mock(\Illuminate\Database\ConnectionInterface::class);
    $grammar = \Mockery::mock(\Illuminate\Database\Grammar::class);
    $processor = \Mockery::mock(\Illuminate\Database\Query\Processors\Processor::class);
    $connection->shouldReceive('getQueryGrammar')->andReturn($grammar);
    $connection->shouldReceive('getPostProcessor')->andReturn($processor);

    return new \Illuminate\Database\Eloquent\Builder(new \Illuminate\Database\Query\Builder($connection));
}