<?php

namespace App\Http\Resources\User\Chart;

use Illuminate\Http\Resources\Json\JsonResource;

class LastResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->resource;

        return [
            'up' => $data['up'] ?? 0,
            'down' => $data['down'] ?? 0,
            'data' => ListLastResultResource::collection($data['data']),
        ];
    }
}
