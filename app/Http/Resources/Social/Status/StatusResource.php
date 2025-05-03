<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'content' => $this->content,
            'font_size' => $this->font_size,
            'font_color' => $this->font_color,
            'font_family' => $this->font_family,
            'video' => $this->video,
            'audio' => $this->audio,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}

