<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\Company;

class UserStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'user' => $this->getCommentOwnerDetails(),
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
                 'name' => $owner instanceof Company ? $owner->name : $owner->first_name . ' ' . $owner->last_name,
                 'image' => $owner->image,
             ];
         }

}

