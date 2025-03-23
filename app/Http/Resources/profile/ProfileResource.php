<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\User\Company;

class ProfileResource extends JsonResource

{
    public function toArray(
        $request,
    ) {
        return [
            'id' => $this->id,
            'user' => $this->getUserDetails(),
            'followers_count' => $this->followers()->count(),
            'friends_count' => $this->friends()->count(),
        ];
    }
    public function getUserDetails()
    {
        $user = optional(
            $this->userable,
        );
        if (!$user) {
            return null;
        }
        $yearsSinceRegistration = Carbon::parse($user->created_at)->diffInYears(Carbon::now());

        return [
            'type' => $user->getMorphClass(),
            'name' => $user instanceof Company ? $user->first_name . ' ' . $user->last_name
                :
                $user->name,
            'job_title' => $user->job_title,
            'image' => $user->image,
            'experience' => $yearsSinceRegistration,
            'cover_image' => $user->cover_image,
        ];
    }
}
