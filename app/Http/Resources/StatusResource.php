<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image_url' => $this->image_url,
            'video_url' => $this->video_url,
            'status_owner' => $this->getStatusOwnerDetails(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
    private function getStatusOwnerDetails()
    {
        $owner = optional($this->user)->userable;
        if (!$owner) {
            return null;
        }
        return [
            'type' => $owner->getMorphClass(),
            'name' => $owner instanceof \App\Models\Company ? $owner->name : $owner->first_name . ' ' . $owner->last_name,
            'image' => $owner->image,
        ];
    }
}
