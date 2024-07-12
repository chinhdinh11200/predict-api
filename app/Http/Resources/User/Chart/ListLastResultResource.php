<?php

namespace App\Http\Resources\User\Chart;

use Illuminate\Http\Resources\Json\JsonResource;

class ListLastResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'session' => $this->id,
            'result' => $this->result,
        ];
    }
}
