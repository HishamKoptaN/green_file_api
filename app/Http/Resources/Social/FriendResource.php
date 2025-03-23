<?php

namespace App\Http\Resources\Social;
use Illuminate\Http\Resources\Json\JsonResource;
class FriendResource extends JsonResource
{
    public function toArray($request)
    {
        $currentUser = auth()->user();
         return [
            'id' => $this->id,
            'is_online' =>true,
            // (bool) $this->is_online,
            'name' => optional($this->userable)->first_name . ' ' . optional($this->userable)->last_name,
            'image' => optional($this->userable)->image,
            'is_pending' => $this->checkPendingStatus($currentUser, $this->resource),

        ];
    }
    private function checkPendingStatus($currentUser, $otherUser)
    {
        $friendship = $currentUser->friendships()
            ->where(function ($query) use ($otherUser) {
                $query->where('friend_id', $otherUser->id)
                    ->orWhere('user_id', $otherUser->id);
            })
            ->first();

        return optional($friendship)->status === 'pending';
    }

}
