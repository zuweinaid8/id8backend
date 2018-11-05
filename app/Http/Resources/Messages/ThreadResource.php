<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Users\UserResource;

class ThreadResource extends BaseResource
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
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'participants' => UserResource::collection($this->participants),
            'unread_count' => $this->unread_count,
            'is_unread' => (bool) $this->unread_count,
            'last_message' => new MessageResource($this->last_message),
        ];
    }
}
