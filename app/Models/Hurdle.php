<?php

namespace App\Models;

use Carbon\Carbon;

class Hurdle extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'to',
        'from',
    ];

    protected $dates = [
        'to',
        'from'
    ];

    protected $with = [
        'solutions',
    ];

    public function solutions()
    {
        return $this->hasMany('\App\Models\Solution', 'hurdle_id', 'id');
    }

    public function scopeActive($query, $when)
    {
        return $query->where('from', '<=', Carbon::now()->format('Y-m-d'))
              ->where('to', '>=', Carbon::now()->format('Y-m-d'));
    }
}
