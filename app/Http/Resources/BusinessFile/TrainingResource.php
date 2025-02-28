<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TrainingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'company' => $this->getCompanyDetails(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getCompanyDetails()
    {
        $company = optional($this->company,
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
