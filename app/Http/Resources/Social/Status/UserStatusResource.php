<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\Company;

class UserStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status_owner' => $this->getStatusOwnerDetails(),
            'statuses' => StatusResource::collection($this->statuses),
        ];
    }

    private function getStatusOwnerDetails()
    {
        $owner = optional($this->userable);
        if (!$owner) {
            return null;
        }

        return [
            'type' => $owner->getMorphClass(),
            'name' => $owner instanceof Company ? $owner->name : ($owner->first_name . ' ' . $owner->last_name),
            'image' => $owner->image,
            'video' => $owner->video,
        ];
    }
}

