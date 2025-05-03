<?php

namespace App\Http\Resources\Social\Post;

use Illuminate\Http\Resources\Json\JsonResource;


class DraftResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image' => $this->image,
            'video' => $this->video,
            'pdf' => $this->pdf,
        ];
    }
}
