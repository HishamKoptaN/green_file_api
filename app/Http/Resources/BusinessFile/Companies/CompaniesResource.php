<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CompaniesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'custom' => $this->custom,
            'user' => $this->getCompanyDetails(),
            'created_at' => $this->created_at->diffForHumans(),

        ];
    }
    private function getCompanyDetails()
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

}
