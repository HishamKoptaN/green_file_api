<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'user' => $this->getUserDetails(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getUserDetails()
    {
        $user = optional($this->user,
    );
        if (!$user) {
            return null;
        }
        return [
            'id' => $user->id,
            'name' => $user->name,
            'image' => $user->image,
        ];
    }
}
