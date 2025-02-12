<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'likes_count' => $this->likes()->count(),
            'isLike' => $this->isLikedByUser(),
            'comments_count' => $this->comments()->count(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
    private function getPostOwnerDetails()
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
    private function isLikedByUser()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
