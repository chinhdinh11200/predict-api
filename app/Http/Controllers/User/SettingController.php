<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SettingResource;
use App\Services\Admin\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get setting key value
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = SettingService::getInstance()->index();

        return $this->sendSuccessResponse(new SettingResource($data));
    }
}
