<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserSetting\UserSettingResource;
use App\Models\UserSetting;
use App\Services\User\UserSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    /**
     * Get user setting
     * 
     * @return JsonResponse
     */
    public function getUserSetting(): JsonResponse
    {
        $user = auth()->guard()->user();
        $data = UserSettingService::getInstance()->withUser($user)->getUserSetting();

        return $this->sendSuccessResponse(new UserSettingResource($data));
    }

    /**
     * Update user setting
     * 
     * @param Request $request
     * @return JsonResponse
    */
    public function updateUserSetting(Request $request)
    {
        $user = auth()->guard()->user();
        $openVolume = $request->get('open_volume') ?? UserSetting::OPEN_VOLUME;
        $showBalance = $request->get('show_balance') ?? UserSetting::SHOW_BALANCE;
        $data = UserSettingService::getInstance()->withUser($user)->updateUserSetting($openVolume, $showBalance);
        
        return $this->sendSuccessResponse($data, trans('response.user_setting.success'));
    }
}
