<?php

namespace App\Http\Resources\Social\Post;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'posts' => $this->collection,
            'meta' => [
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'total_pages' => $this->resource->lastPage(),
                'has_next_page' => $this->resource->hasMorePages(),
                'has_previous_page' => $this->resource->currentPage() > 1,
                'total' => $this->resource->total(),
            ],
        ];
    }
}
