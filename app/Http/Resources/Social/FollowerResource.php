<?php

namespace App\Http\Resources\Social;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
{
    public function toArray($request)
    {
        $currentUser = auth()->user();

        return [
            'id' => $this->id,
            'is_online' => true, // يمكنك استبداله بــ (bool) $this->is_online إذا كان متوفراً في الجدول
            'name' => optional($this->userable)->first_name . ' ' . optional($this->userable)->last_name,
            'image' => optional($this->userable)->image,
            'is_following' => $this->checkFollowingStatus($currentUser, $this->resource),
        ];
    }

    private function checkFollowingStatus($currentUser, $otherUser)
    {
        return $currentUser->followings()->where('followed_id', $otherUser->id)->exists();
    }
}
