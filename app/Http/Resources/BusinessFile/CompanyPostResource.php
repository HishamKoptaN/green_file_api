<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyPostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'post_owner' => $this->getCompanyDetails(),
            'description' => $this->description,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }

    private function getCompanyDetails()
    {
        if (!$this->company) {
            return null;
        }

        return [
            'id' => $this->company->id,
            'name' => $this->company->name,
            'image' => $this->company->image,
        ];
    }
}
