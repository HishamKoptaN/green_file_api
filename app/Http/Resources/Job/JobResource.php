<?php

namespace App\Http\Resources\Job;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'job_type' => $this->job_type,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'currency' => $this->currency,
            'company' => $this->getCompanyDetails(),
            'job_applicants_count' => $this->jobApplications()->count(),
            'is_applied' => $this->isUserApplied(),
            'skills' => $this->skills->pluck('name'),
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
