<?php

namespace App\Http\Resources\User\Bet;

use App\Models\Bet;
use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
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
            'user_id' => $data->user_id,
            'session_id' => $data->session_id,
            'amount' => $data->amount,
            'is_demo' => $data->is_demo,
            'is_demo_text' => trans("response.bet.demo.{$data->is_demo}"),
            'bet_type' => $data->bet_type,
            'bet_type_text' => trans("response.bet.bet_type.{$data->bet_type}"),
            'is_result' => $data->is_result,
            'result' => $data->result,
            'type' => $data->type,
            'type_text' => $data->type == Bet::SUB ? '-' : '+',
            'reward' => $data->reward,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ];
    }
}
