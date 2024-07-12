<?php

namespace App\Http\Resources\User\Pool;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryPoolCollection extends ResourceCollection
{
    /**
     * Transform resource collection to array
     * 
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $paginator = $this->resource;
        
        return [
            'data' => HistoryPoolResource::collection($paginator),
            'per_page' => $paginator->perPage(),
            'total_page' => $paginator->lastPage(),
            'current_page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];
    }
}
