<?php

namespace App\Http\Resources\PostCmnt;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCmntCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'cmnts' => $this->collection,
            'meta' => [
                'post_id' => $this->resource->first()?->post_id,
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'total_comments' => $this->total(),
                'per_page' => $this->perPage(),
                'has_next_page' => $this->hasMorePages(),
                'has_previous_page' => $this->currentPage() > 1,
            ],
        ];
    }
}
