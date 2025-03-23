<?php

namespace App\Http\Resources\Social;

use Illuminate\Http\Resources\Json\JsonResource;

class SuggestedUserResource extends JsonResource
{
    public function toArray($request)
    {
        $userable = $this->userable;

        return [
            'id' => $this->id,
            'user' => $this->getUserDetails(
                $userable,
            ),
        ];
    }

    private function getUserDetails($userable)
    {
        if (!$userable) {
            return null;
        }
        $type = class_basename($userable);
        $name = match ($type) {
            'Company' => $userable->name,
            'Seeker' => $userable->first_name . ' ' . $userable->last_name,
            default => 'Unknown',
        };
        return [
            'type' => $type,
            'name' => $name,
            'image' => $userable->image,
        ];
    }
}
