<?php namespace App\Filters\Hurdle;

use EloquentFilter\ModelFilter;

class AssignmentFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function keyword($title)
    {
        return $this->where(function ($q) use ($title) {
            $q->where('title', 'ILIKE', "%$title%")
                ->orWhere('description', 'ILIKE', "%$title%");
        });
    }
}
