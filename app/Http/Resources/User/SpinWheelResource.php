<?php

namespace App\Http\Resources\User;

use App\Helpers\DateHelper;
use App\Helpers\LuckyWheelHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpinWheelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = $this->resource;

        return [
            'id' => $data->id,
            'type' => LuckyWheelHelper::getPrizeType($data->id),
            'reward' => $data->reward,
            'reward_format' => LuckyWheelHelper::getPrizeFormat($data->reward),
            'created_at' => DateHelper::formatDateTimeFull($data->created_at),
            'updated_at' => DateHelper::formatDateTimeFull($data->updated_at),
        ];
    }
}
