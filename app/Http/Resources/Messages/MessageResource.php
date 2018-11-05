<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Files\FileResource;
use App\Http\Resources\Users\UserResource;

class MessageResource extends BaseResource
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
            'sender' => new UserResource($this->sender),
            'sender_id' => $this->sender_id,
            'body' =>  $this->body,
            'attachment_file_id' => $this->attachment_file_id,
            'attachment' => new FileResource($this->attachment),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_edited' => $this->is_edited,
            'i_am_sender' => $this->i_am_sender,
        ];
    }
}
