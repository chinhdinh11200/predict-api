<?php

namespace App\Http\Resources\User\UserSetting;

use App\Models\UserSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingResource extends JsonResource
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
            'open_volume' => $data->open_volume,
            'show_balance' => $data->show_balance,
        ];
    }
}
