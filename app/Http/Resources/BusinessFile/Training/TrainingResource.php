<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TrainingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'custom' => $this->custom,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
