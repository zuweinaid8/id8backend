<?php namespace App\Filters\Files;

use EloquentFilter\ModelFilter;

class FileFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($name)
    {
        $this->where('name', 'ilike', "%$name%");
    }
}
