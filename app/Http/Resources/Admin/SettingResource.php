<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'trade_min' => (float)$this['trade_min'],
            'withdraw_fee' => (float)$this['withdraw_fee'],
            'start_golden_hour' => $this['start_golden_hour'],
            'end_golden_hour' => $this['end_golden_hour'],
            'ticket_price' => (float)$this['ticket_price'],
        ];
    }
}
