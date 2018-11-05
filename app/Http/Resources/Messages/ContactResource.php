<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\BaseResource;

class ContactResource extends BaseResource
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
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'meta' => $this->meta,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'profile_picture_file_id' => $this->profile_picture_file_id,
        ];
    }
}
