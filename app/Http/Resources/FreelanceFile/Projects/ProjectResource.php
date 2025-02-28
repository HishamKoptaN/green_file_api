<?php

namespace App\Http\Resources\FreelanceFile\Projects;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' =>    $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' =>  $this->created_at->diffForHumans(),
        ];
    }
}
