<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCollection extends ResourceCollection
{
    protected $dataKey;

    /**
     * PaginatedCollection constructor.
     *
     * @param  mixed  $resource
     * @param  string  $dataKey اسم المفتاح الذي سيظهر في النتيجة (مثل: news, users, products, …)
     */
    public function __construct($resource, $dataKey = 'data')
    {
        parent::__construct($resource);
        $this->dataKey = $dataKey;
    }

    public function toArray($request)
    {
        return [
            $this->dataKey => $this->collection,
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
