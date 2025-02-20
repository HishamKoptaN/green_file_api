<?php

namespace App\Http\Resources\Opinion;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\BusinessFile\Opinion;

class OpinionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'opinions' => $this->collection,
            'meta' => [
                'total' => $this->resource->total(),
                'total_pages' => $this->resource->lastPage(),
                'current_page' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage(),
                'has_next_page' => $this->resource->hasMorePages(),
                'has_previous_page' => $this->resource->currentPage() > 1,
            ],
        ];
    }
}
