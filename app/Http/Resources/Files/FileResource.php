<?php

namespace App\Http\Resources\Files;

use App\Http\Resources\BaseResource;

class FileResource extends BaseResource
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
            'name' => $this->name,
            'owner' => $this->owner,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'notes' => $this->notes,
            'is_public' => (bool)$this->is_public,
            'meta' => $this->meta,
            'url' => $this->url,
            'is_image' => (bool) $this->is_image,
            'is_video' => (bool) $this->is_video,
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
            'permissions' => $this->permissions,
        ];
    }
}
