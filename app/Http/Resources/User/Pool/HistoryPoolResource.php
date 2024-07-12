<?php

namespace App\Http\Resources\User\Pool;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryPoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource;
        
        return [
            'id' => $data->id,
            'user_id' => $data->user_id,
            'bet_id' => $data->bet_id,
            'value' => $data->value,
            'type' => $data->type,
            'type_text' => trans('response.pool.type.' . $data->type),
            'note' => $data->note,
        ];
    }
}
