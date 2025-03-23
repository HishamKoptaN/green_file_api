<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User\OpportunityLooking;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
            'user' => $this->getUserDetails(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getUserDetails()
    {
        $user = $this->user;
        if (!$user) {
            return null;
        }
        $opportunityLooking = $user->userable instanceof OpportunityLooking ? $user->userable : null;
        return [
            'id' => $user->id,
            'name' => $opportunityLooking ? $opportunityLooking->name : $user->name,
            'image' => $opportunityLooking ? $opportunityLooking->image : $user->image,
        ];
    }
}
