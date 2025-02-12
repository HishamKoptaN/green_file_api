<?php

namespace App\Http\Resources\PostCmnt;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostCmntResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'image_url' => $this->image_url,
            'video_url' => $this->video_url,
            'comment_owner' => $this->getCommentOwnerDetails(),
            'isOwner' => optional($this->user)->id === Auth::guard('sanctum')->user()->id,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getCommentOwnerDetails()
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
