<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\User;
use App\Models\User\Company;

class UserResource extends JsonResource

{
    public function toArray(
        $request,
    ) {
        return [
            'id' => $this->id,
            'role' => $this->getRoleNames()->first(),
            'user' => $this->getUserDetails(),
        ];
    }
    public function getUserDetails()
    {
        $user = optional($this->userable,);
        if (!$user) {
            return null;
        }
        return [
            'type' => $user->getMorphClass(),
            'name' => $user instanceof Company ? $user->first_name . ' ' . $user->last_name
                :
                $user->name,
            'image' => $user->image,
            'cover_image' => $user->cover_image,
        ];
    }
}
