<?php

namespace App\Http\Resources\Chats;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\Company;

class ChatResource extends JsonResource
{
    public function toArray(
        $request,
    ) {
        $authId = auth()->id();
        $otherUser = $this->user_1_id == $authId ? $this->user2 : $this->user1;
        $owner = optional($otherUser->userable);
        return [
            'id' => $this->id,
            'uuid' => auth()->user()->firebase_uid,
            'other_user' => $owner ? [
                'id' => $otherUser->id,
                'type' => $owner->getMorphClass(),
                'name' => $owner instanceof \App\Models\User\Company
                    ? $owner->name
                    : trim("{$owner->first_name} {$owner->last_name}"),
                'image' => $owner->image,
                'video' => $owner->video,
            ] : null,
        ];
    }
}
