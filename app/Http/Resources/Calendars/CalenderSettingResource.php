<?php

namespace App\Http\Resources\Calendars;

use App\Http\Resources\BaseResource;

class CalenderSettingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_public' => (bool)$this->is_public,
            'notifications_enabled' => (bool)$this->notifications_enabled,
            'timezone' => $this->timezone,
            'location' => $this->location,
        ];
    }
}
