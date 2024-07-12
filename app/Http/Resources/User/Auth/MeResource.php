<?php

namespace App\Http\Resources\User\Auth;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
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
            'username' => $data->username,
            'fullname' => $data->fullname,
            'email' => $data->email,
            'total_tickets' => $data->total_tickets,
            'refcode' => $data->refcode,
            'level' => $data->level,
            'agency_status' => $data->agency_status,
            'avatar' => FileHelper::getFullUrl($data->avatar),
        ];
    }
}
