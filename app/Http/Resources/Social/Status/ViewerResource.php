<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\Company;

class ViewerResource extends JsonResource
{
    protected $status;

    public function __construct($resource, $status = null)
    {
        parent::__construct($resource);
        $this->status = $status;
    }
    public function toArray($request)
    {
        $isLiked = $this->status
            ? $this->status->likes()->where('user_id', $this->id)->exists()
            : false;
        return [
            'id' => $this->id,
            'user' => $this->getMessageOwnerDetails() ?? null,
            'message' => $this->pivot->message ?? null,
            'is_liked' => $isLiked,
        ];
    }
    private function getMessageOwnerDetails()
    {
        $owner = optional($this->userable);

        return [
            'type' => $owner ? $owner->getMorphClass() : null,
            'name' => $owner
                ? ($owner instanceof Company
                    ? $owner->name
                    : trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? '')))
                : null,
            'image' => $owner->image ?? null,
        ];
    }
}
