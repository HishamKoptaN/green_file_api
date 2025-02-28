<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'post_owner' => $this->getCompanyDetails(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getCompanyDetails()
    {
        $company = optional(
            $this->company,
    );
        if (!$company) {
            return null;
        }
        return [
            'id' => $company->id,
            'name' => $company->name,
            'image' => $company->image,
        ];
    }
}
