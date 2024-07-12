<?php

namespace App\Http\Resources\User\Commission;

use App\Helpers\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryCommissionResource extends JsonResource
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

        $fromUsername = '';
        if ($data->fromUser) $fromUsername = $data->fromUser->fullname ?: $data->fromUser->username;

        return [
            'id' => $data->id,
            'type' => $data->type,
            'type_text' => trans('response.commission.type.' . $data->type),
            'user_id' => $data->user_id,
            'from_user_id' => $data->from_user_id,
            'from_user_name' => $fromUsername,
            'value' => $data->value,
            'value_format' => StringHelper::convertIntToCurrencyText($data->value),
            'note' => $data->note,
            'created_at' => $data->created_at_formatted,
        ];
    }
}
