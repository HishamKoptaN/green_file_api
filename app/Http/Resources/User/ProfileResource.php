<?php

namespace App\Http\Resources\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User\Company;
use App\Models\User\OpportunityLooking;



class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this instanceof OpportunityLooking) {
            return [
                'type' => 'opportunity_looking',
                'field' => $this->field,
                'experience' => $this->experience,
            ];
        } elseif ($this instanceof Company) {
            return [
                'type' => 'company',
                'company_name' => $this->company_name,
                'industry' => $this->industry,
            ];
        }

        return [];
    }
}
