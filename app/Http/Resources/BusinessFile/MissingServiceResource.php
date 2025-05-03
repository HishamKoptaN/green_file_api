<?php

namespace App\Http\Resources\BusinessFile;

use Illuminate\Http\Resources\Json\JsonResource;

class MissingServiceResource extends JsonResource
{
    public function toArray(
        $request,
    ) {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'specialization_id' => $this->specialization_id,
            'details' => $this->details,
        ];
    }
}
