<?php

namespace App\Services\User;

use App\Models\UserSetting;
use App\Services\Service;

class UserSettingService extends Service
{
    /**
     * Get user setting
     * 
     * @return UserSetting
     */
    public function getUserSetting(): UserSetting
    {
        $userSetting = UserSetting::query()->where('user_id', $this->user->id)->first();

        return $userSetting;
    }

    /**
     * Update user setting
     * 
     * @param $openVolume
     * @param $showBalance
     * @return boolean
     */
    public function updateUserSetting($openVolume, $showBalance): bool
    {
        try {
            UserSetting::query()
            ->where('user_id', $this->user->id)
            ->update([
                'open_volume' => $openVolume,
                'show_balance' => $showBalance
            ]);
            
            return true;
        } catch (\Exception $e) {
            logger($e->getMessage(), [$e]);
            return false;
        }
    }
}
