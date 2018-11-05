<?php

namespace App\Models\Calendars;

use App\Models\BaseModel as Model;
use App\Observers\ModelHasOwner;
use App\Traits\AuditsUserTrait;

class Calendar extends Model
{
    use AuditsUserTrait;

    protected $fillable =[
        'name',
        'description',
    ];

    public static function boot()
    {
        // Append owner id on creating
        static::observe(ModelHasOwner::class);

        parent::boot();
    }

    public function settings()
    {
        return $this->hasOne(CalendarSetting::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
