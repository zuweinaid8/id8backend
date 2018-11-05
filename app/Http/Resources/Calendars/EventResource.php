<?php

namespace App\Http\Resources\Calendars;

use App\Http\Resources\BaseResource;

class EventResource extends BaseResource
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
            'id' => $this->id,
            'calendar_id' => $this->calendar_id,
            'title' => $this->title,
            'description' => $this->description,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'location' => $this->location,
            'color_id' => $this->color_id,
            'meta' => $this->meta,
            'owner' => $this->owner,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
            'participants' => [
                'admins' => $this->participants->admins ?? false,
                'startups' => $this->participants->startups ?? false,
                'investors' => $this->participants->investors ?? false,
                'mentors' => $this->participants->mentors ?? false,
            ],
        ];
    }
}
