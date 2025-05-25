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
        $isLiked = false;
        if ($this->status) {
            $isLiked = $this->status->likes()
                ->where('user_id', $this->user_id ?? $this->id)
                ->exists();
        }

        return [
            'id' => $this->id,
            'user' => $this->getCommentOwnerDetails(),
            'message' => $this->message,
            'is_liked' => $isLiked,
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
            'name' => $owner instanceof Company
                ? $owner->name
                : $owner->first_name . ' ' . $owner->last_name,
            'image' => $owner->image,
        ];
    }
}
