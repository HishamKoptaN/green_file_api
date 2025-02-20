<?php

namespace App\Http\Resources\Job;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Job\Job;

class JobCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'jobs' => $this->collection,
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
