<?php

namespace App\Http\Resources\User;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryTransactionResource extends JsonResource
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
            'tx' => $data->tx ?? trans("transaction.tx.{$data->type}"),
            'username' => $data->username,
            'address' => $data->address,
            'amount' => $data->amount,
            'fee' => $data->fee,
            'note' => $data->note,
            'type' => $data->type,
            'type_lang' => trans("transaction.type_lang.{$data->type}"),
            'status' => $data->status,
            'created_at' => DateHelper::formatDateTimeFull($data->created_at),
            'updated_at' => DateHelper::formatDateTimeFull($data->updated_at),
        ];
    }
}
