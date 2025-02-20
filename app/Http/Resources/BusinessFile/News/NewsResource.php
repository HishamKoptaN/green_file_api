<?php

namespace App\Http\Resources\BusinessFile\News;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'company' => $this->getCompanyDetails(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
    private function getCompanyDetails()
    {
        $company = optional($this->company);
        if (!$company) {
            return null;
        }
        return [
            'id' => $company->id,
            'name' => $company->name,
            'image' => $company->image,
        ];
    }


    private function isUserApplied()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return false;
        }
        return $this->jobApplications()->where('user_id', $user->id)->exists();
    }
}
