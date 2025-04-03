<?php

namespace App\Http\Resources\Social\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Company;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image' => $this->image,
            'video' => $this->video,
            'post_owner' => $this->getPostOwnerDetails($this->user),
            'original_post' => $this->getOriginalPostDetails(),
            'likes_count' => $this->likes()->count(),
            'isLike' => $this->isLikedByUser(),
            'comments_count' => $this->comments()->count(),
            'type' => $this->type,
            'created_at' => $this->created_at->diffForHumans(),
            'publish_at' => $this->publish_at,
        ];
    }

    private function getPostOwnerDetails($user)
    {
        if (!$user || !$user->userable) {
            return [
                'id' => null,
                'type' => 'Unknown',
                'name' => 'Anonymous',
                'image' => null,
                'is_following' => false,
            ];
        }

        $owner = $user->userable;
        $authUser = Auth::guard('sanctum')->user();
        $isFollowing = $authUser ? $authUser->following()->where('followed_id', $user->id)->exists() : false;
        return [
            'id' => $owner->id,
            'type' => $owner->getMorphClass(),
            'name' => $owner instanceof Company ? $owner->name : "{$owner->first_name} {$owner->last_name}",
            'image' => $owner->image,
            'is_following' => $isFollowing,
        ];
    }

    private function getOriginalPostDetails()
    {
        if (!$this->original_post_id) {
            return null;
        }

        $originalPost = $this->originalPost;

        if (!$originalPost) {
            return null;
        }

        return [
            'id' => $originalPost->id,
            'content' => $originalPost->content,
            'image' => $originalPost->image,
            'video' => $originalPost->video,
            'created_at' => $originalPost->created_at->diffForHumans(),
            'post_owner' => $this->getPostOwnerDetails($originalPost->user),
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
