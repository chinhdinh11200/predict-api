<?php

namespace App\Http\Resources\User;

use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class RewardPrizeResource extends JsonResource
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
        $lang = App::getLocale() ?? 'vi';
        $name = "name_$lang";
        $prize = $data->luckyWheel ? $data->luckyWheel->$name : null;

        return [
            'id' => $data->id,
            'prize' => $prize,
            'value' => $data->value,
            'value_text' => StringHelper::convertIntToCurrencyText((int)$data->value, $prize),
            'created_at' => DateHelper::formatDateTime($data->created_at),
        ];
    }
}
