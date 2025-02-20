<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image_url' => $this->image_url,
            'video_url' => $this->video_url,
            'post_owner' => $this->getPostOwnerDetails(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
