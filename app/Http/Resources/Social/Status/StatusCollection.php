<?php

namespace App\Http\Resources\Social\Status;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StatusCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'statuses' => StatusResource::collection($this->collection),
            'meta' => [
                'per_page' => $this->when(method_exists($this->resource, 'perPage'), fn() => $this->resource->perPage()),
                'current_page' => $this->when(method_exists($this->resource, 'currentPage'), fn() => $this->resource->currentPage()),
                'last_page' => $this->when(method_exists($this->resource, 'lastPage'), fn() => $this->resource->lastPage()),
                'has_next_page' => $this->when(method_exists($this->resource, 'hasMorePages'), fn() => $this->resource->hasMorePages()),
                'has_previous_page' => $this->when(method_exists($this->resource, 'currentPage'), fn() => $this->resource->currentPage() > 1),
                'total' => $this->when(method_exists($this->resource, 'total'), fn() => $this->resource->total()),
            ],
        ];
    }
}
