<?php

namespace App\Http\Resources\BusinessFile\OpinionPolls;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpinionPollOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'option' => $this->option,
            'votes'  => $this->votes ?? 0,
        ];
    }
}
