<?php

namespace App\Services\User;

use App\Models\HistoryCommission;
use App\Models\User;
use App\Models\UserRelationship;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class UserService extends Service
{
    /**
     * Increase usdt balance
     *
     * @param $userId
     * @param $balance
     */
    public function increaseUsdtBalance($userId, $balance)
    {
        User::query()
            ->where('id', $userId)
            ->update([
                'usdt_balance' => DB::raw('`usdt_balance` + ' . $balance),
            ]);
    }

    /**
     * Reduce usdt balance
     *
     * @param $userId
     * @param $balance
     */
    public function reduceUsdtBalance($userId, $balance)
    {
        User::query()
            ->where('id', $userId)
            ->update([
                'usdt_balance' => DB::raw('`usdt_balance` - ' . $balance),
            ]);
    }

    /**
     * Get vip member, total commission
     *
     * @return array
     */
    public function vipMember()
    {
        $user = $this->user;
        $userRelation = UserRelationship::query()->where('user_id', $user->id)->first();
        $totalRefer = 0;
        if ($userRelation) {
            $totalRefer = $userRelation->descendants()->count();
        }
        $isRegular = false;
        $isVip = false;
        switch ($user->agency_status) {
            case User::AGENCY_STATUS_VIP:
                $isVip = true;
                $license = User::AGENCY_STATUS_VIP;
                break;
            case User::AGENCY_STATUS_REGULAR:
                $isRegular = true;
                $license = User::AGENCY_STATUS_REGULAR;
                break;
            default:
                $license = null;
                break;
        }

        return [
            'license' => $license,
            'license_text' => $license ? trans("response.user.agency_status.{$license}") : '',
            'level' => $user->level,
            'total_refer' => $totalRefer,
            'total_commission' => HistoryCommission::query()->where('user_id', $user->id)->sum('value'),
            'is_vip' => $isVip,
            'is_regular' => $isRegular,
            'refcode' => $user->refcode,
        ];
    }
}
