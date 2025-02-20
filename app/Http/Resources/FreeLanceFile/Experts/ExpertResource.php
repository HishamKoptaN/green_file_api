<?php

namespace App\Http\Resources\Experts;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ExpertResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user' => $this->getUserDetails(),
            'job_applicants_count' => $this->jobApplications()->count(),
            'is_applied' => $this->isUserApplied(),
            'skills' => $this->skills->pluck('name'),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getUserDetails()
    {
        $user = optional($this->user);
        if (!$user) {
            return null;
        }
        return [
            'id' => $user->id,
            'name' => $user->name,
            'image' => $user->image,
        ];
    }

    private function isUserApplied()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return false;
        }
        return $this->jobApplications()->where('user_id', $user->id)->exists();
    }
}
