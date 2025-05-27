<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StatusResource extends JsonResource
{

    public function toArray($request)
    {
        $user = Auth::guard('sanctum')->user();
        $hasSeen = false;
        $isLiked = false;
        if ($user) {
            $hasSeen = $this->views()->where('user_id', $user->id)->exists();
            $isLiked = $this->likes()->where('user_id', $user->id)->exists();

        }
        return [
            'id' => $this->id,
            'image' => $this->image,
            'content' => $this->content,
            'font_size' => $this->font_size,
            'font_color' => $this->font_color,
            'font_family' => $this->font_family,
            'video' => $this->video,
            'thumbnail_url' => $this->thumbnail_url,
            'audio' => $this->audio,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'is_seen' => $hasSeen,
            'is_liked' => $isLiked,
            'views_count' => $this->viewers()->count(),

        ];
    }

}

