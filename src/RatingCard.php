<?php

namespace NovaCards\RatingCard;


use Illuminate\Database\Schema\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Metric;

class RatingCard extends Metric
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * @var int
     */
    public $max;
    /**
     * @var string
     */
    public $column;

    /**
     * @var string|Builder
     */
    public $model;

    /**
     * RatingCard constructor.
     * @param int $max
     * @param string|Builder $model
     * @param string $column
     * @param null|string $component
     */
    public function __construct(?string $name, int $max, $model, $column = 'rating', ?string $component = null)
    {
        parent::__construct($component);
        $this->withMax($max);
        $this->column = $column;
        $this->model = $model;
        $this->name = $name;
    }

    public function withMax(int $max)
    {
        $this->max = $max;
        return $this->withMeta(['max' => $max]);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'rating-card';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->average($request, $this->model, $this->column);
    }

    public function uriKey()
    {
        return 'rating-average';
    }



    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  string  $column
     * @param  string  $groupBy
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    protected function average($request, $model, $column)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
            $column = $column ?? $query->getModel()->getQualifiedKeyName()
        );

        $result = $query->select(
            DB::raw("avg({$wrappedColumn}) as aggregate")
        )->first();

        return number_format($result->aggregate, 0);
    }

}
