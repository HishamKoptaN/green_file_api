<?php

namespace App\Http\Resources\BusinessFile\OpinionPolls;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OpinionPollResource extends JsonResource
{
    public function toArray($request)
    {
        $user = Auth::user();
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'options'    => OpinionPollOptionResource::collection($this->options),
            'company' => $this->getCompanyDetails(),
            'selected_option' => $this->getUserSelectedOption($user),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
    private function getCompanyDetails()
    {
        $company = $this->company;
        if (!$company) {
            return null;
        }
        return [
            'id'    => $company->id,
            'name'  => $company->name,
            'image' => $company->image,
        ];
    }

    private function getUserSelectedOption($user)
    {
        if (!$user) {
            return null;
        }

        $response = $this->responses()
            ->where('user_id', $user->id)
            ->first();

        if (!$response) {
            return null;
        }

        return $response->opinion_poll_option_id;  // <-- فقط رقم الاختيار
    }

}
