<?php namespace App\Filters\Messages;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\DB;

class ThreadFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function search($keyword)
    {
        return $this->where(/**
         * @param $q
         * @return mixed
         */
            function($q) use($keyword){
            return $q->where(DB::raw('lower(title)'), 'LIKE', strtolower("%$keyword%"))
                ->orWhere(DB::raw('lower(description)'), 'LIKE', strtolower("%$keyword%"));
        });
    }
}
