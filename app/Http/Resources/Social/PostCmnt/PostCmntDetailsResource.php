<?php

namespace App\Http\Resources\PostCmnt;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Post\PostResource;
use App\Models\User\Company;

class PostCmntDetailsResource extends JsonResource
{
    public function toArray(
        $request,
    ) {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => $this->getPostOwnerDetails(),
            'post' => new PostResource($this->post),
            'created_at' => $this->created_at->diffForHumans(),
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
            'name' => $owner instanceof Company ? $owner->name : $owner->first_name . ' ' . $owner->last_name,
            'image' => $owner->image,
        ];
    }
}
