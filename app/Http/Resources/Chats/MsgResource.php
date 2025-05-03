<?php

namespace App\Http\Resources\Chats;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MsgResource extends JsonResource
{
    public function toArray($request)
    {
        $authId = auth()->id();
        $owner = optional(optional($this->user)->userable);
        $isCurrentUser = $this->user_id == $authId;

        return [
            'id' => $this->id,
            'msg' => $this->msg,
            'image' => $owner->image,
            'video' => $owner->video,
            'is_current_user' => $isCurrentUser,
            'seen_at' => $this->read_at ? $this->read_at->diffForHumans() : null,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
        ];
    }
}
