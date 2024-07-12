<?php

namespace App\Http\Resources\User\Bet;

use App\Helpers\DateHelper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeResource extends JsonResource
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
            'id' => $data->id,
            'start_time' => $data->chart ? DateHelper::formatDateTimeFromTimeStamp($data->chart->start_time / 1000) : null,
            'bet_type' => $data->bet_type,
            'bet_type_text' => trans("response.bet.bet_type.{$data->bet_type}"),
            'open_price' => $data->chart ? $data->chart->open_price : null,
            'close_price' => $data->chart ? $data->chart->close_price : null,
            'trade_amount' => $data->amount,
            'payout' => $data->reward,
            'user_id' => $data->user_id,
            'session_id' => $data->session_id,
            'is_demo' => $data->is_demo,
            'is_demo_text' => trans("response.bet.demo.{$data->is_demo}"),
            'is_result' => $data->is_result,
            'result' => $data->result,
            'type' => $data->type,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ];
    }
}
