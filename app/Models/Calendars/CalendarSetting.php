<?php

namespace App\Models\Calendars;

use App\Models\BaseModel as Model;

class CalendarSetting extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'is_public',
        'notifications_enabled',
        'timezone',
        'location',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'notifications_enabled' => 'boolean',
    ];

    protected $touches = [
        'calendar',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
