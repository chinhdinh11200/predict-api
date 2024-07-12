<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingAdminRequest;
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

    /**
     * Update setting key value
     *
     * @param SettingAdminRequest $request
     * @return JsonResponse
     */
    public function update(SettingAdminRequest $request): JsonResponse
    {
        $inputs = $request->only([
            'trade_min',
            'withdraw_fee',
            'start_golden_hour',
            'end_golden_hour',
        ]);
        $data = SettingService::getInstance()->update($inputs);

        return $this->sendSuccessResponse($data, trans('response.updated', [
            'object' => trans('response.label.setting')
        ]));
    }
}
