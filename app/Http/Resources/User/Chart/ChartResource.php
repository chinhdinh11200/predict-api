<?php

namespace App\Http\Resources\User\Chart;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartResource extends JsonResource
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
            'session_id' => $data->id,
            'start_time' => $data->chart ? $data->chart->start_time : $data->start_time,
            'end_time' => $data->chart ? $data->chart->end_time : $data->end_time,
            'open_price' => $data->chart ? $data->chart->open_price : 0,
            'close_price' => $data->chart ? $data->chart->close_price : 0,
            'low_price' => $data->chart ? $data->chart->low_price : 0,
            'high_price' => $data->chart ? $data->chart->high_price : 0,
            'volume' => $data->chart ? $data->chart->volume : 0,
        ];
    }
}
