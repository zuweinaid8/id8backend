<?php

namespace App\Http\Resources\Assignments;

use App\Http\Resources\BaseResource;

class AssignmentResource extends BaseResource
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
            'mentorship_id' => $this->mentorship_id,
            'title' => $this->title,
            'description' => $this->description,
            'meta' => $this->meta,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mentorship' => $this->whenLoaded('mentorship'),
            'settings' => new AssignmentSettingsResource($this->whenLoaded('settings')),
            'submissions' => $this->whenLoaded('submissions'),
            'grades' => $this->whenLoaded('grades'),
        ];
    }
}
