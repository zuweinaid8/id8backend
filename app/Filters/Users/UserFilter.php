<?php namespace App\Filters\Users;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\DB;

class UserFilter extends ModelFilter
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
        return $this->where(function($q) use($keyword){
            return $q->where(DB::raw('lower(name)'), 'LIKE', strtolower("%$keyword%"))
                ->orWhere(DB::raw('lower(email)'), 'LIKE', strtolower("%$keyword%"));
        });
    }
}
