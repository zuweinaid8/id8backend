<?php

namespace App\Models\Calendars;

use App\Models\BaseModel as Model;
use App\Observers\ModelHasOwner;
use App\Traits\AuditsUserTrait;
use App\User;
use Carbon\Carbon;

class Event extends Model
{
    use AuditsUserTrait;

    protected $table = 'calendar_activities';

    protected $attributes = [
        'type' => 'event',
        'color_id' => '616164',
        'meta' => '{}',
    ];

    protected $fillable = [
        'title',
        'description',
        'start_at',
        'end_at',
        'location',
        'color_id',
        'meta',
        'participants',
    ];

    protected $casts =[
        'calendar_id' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'meta' => 'object',
        'participants' => 'object',
    ];

    public static function boot()
    {
        // Append owner id on creating
        static::observe(ModelHasOwner::class);

        parent::boot();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = new Carbon($value);
    }

    public function setEndAtAttribute($value)
    {
        $this->attributes['end_at'] =  new Carbon($value);
    }
}
