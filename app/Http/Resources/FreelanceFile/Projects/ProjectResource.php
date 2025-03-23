<?php

namespace App\Http\Resources\FreelanceFile\Projects;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\OpportunityLooking;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
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
            'name' => $opportunityLooking ? $opportunityLooking->name : $user->name, // ✅ جلب الاسم من OpportunityLooking إذا كان متاحًا
            'image' => $opportunityLooking ? $opportunityLooking->image : $user->image, // ✅ جلب الصورة من OpportunityLooking إذا كانت متاحة
        ];
    }
}
