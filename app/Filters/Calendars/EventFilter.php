<?php namespace App\Filters\Calendars;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class EventFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * @param $start_at
     * @return EventFilter
     */
    public function startAt($start_at)
    {
        return $this->where('end_at', '>=', $start_at);
    }

    /**
     * @param $end_at
     * @return mixed
     */
    public function endAt($end_at)
    {
        return $this->where('start_at', '<=', $end_at);
    }

    /**
     * @param $value
     * @return EventFilter
     */
    public function date($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value);
        $date->timezone(config('app.timezone'));

        $start_at = $date->startOfDay();
        $end_at = $date->endOfDay();

        return $this->where('start_at', '<=', $end_at)
            ->where('end_at', '<=', $start_at);
    }
}
