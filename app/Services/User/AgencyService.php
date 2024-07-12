<?php

namespace App\Services\User;

use App\Exceptions\InputException;
use App\Jobs\UpdateRankUserJob;
use App\Models\HistoryCommission;
use App\Models\LevelCondition;
use App\Models\NormalAgencyCondition;
use App\Models\Setting;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserRelationship;
use App\Models\UserSetting;
use App\Models\VipAgencyCondition;
use App\Services\Common\SystemWallerService;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgencyService extends Service
{
    /**
     * Buy vip
     *
     * @return bool
     * @throws InputException
     */
    public function buyVip(): bool
    {
        $user = $this->user;
        if ($user->agency_status == User::AGENCY_STATUS_VIP) {
            throw new InputException(trans('response.agency.vip'));
        }//end if

        return $this->buyAgency(User::AGENCY_STATUS_VIP, 'vip_agency_fee', 6);
    }

    /**
     * Buy normal
     *
     * @return bool
     * @throws InputException
     */
    public function buyNormal(): bool
    {
        return $this->buyAgency(User::AGENCY_STATUS_REGULAR, 'basic_agency_fee', 0);
    }

    /**
     * Buy agency
     *
     * @param $agencyStatus
     * @param $settingKey
     * @param int $newLevel
     * @return bool
     * @throws InputException
     */
    private function buyAgency($agencyStatus, $settingKey, int $newLevel): bool
    {
        $user = $this->user;
        $userSetting = $user->setting;
        $fee = Setting::query()->where('key', $settingKey)->first();

        if (!$fee) {
            throw new InputException(trans('response.not_enough'));
        }//end if

        if ((float)$user->usdt_balance < (float)$fee->value) {
            throw new InputException(trans('bet.not_enough'));
        }//end if

        $userNode = UserRelationship::query()->where('user_id', $user->id)->first();
        $allParentNode = $userNode->ancestors()->withDepth()->orderByDesc('parent_id')->get();

        Log::channel('agency')->info('=============================================================');
        DB::beginTransaction();
        try {
            $user = User::query()->where('id', $user->id)->lockForUpdate()->first();
            $user->usdt_balance -= (float)$fee->value;
            if ($agencyStatus == User::AGENCY_STATUS_VIP
                && $user->level < 6
            ) {
                $user->level = $newLevel;
            }//end if

            $user->agency_status = $agencyStatus;
            $user->save();

            TransactionService::getInstance()->agencyTransaction(
                $user->id,
                (float)$fee->value,
                $agencyStatus == User::AGENCY_STATUS_VIP
                    ? TransactionDetail::TRANSACTION_TYPE_BUY_VIP
                    : TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL
            );

            if ($userSetting->is_marketing == UserSetting::NONE_MARKETING) {
                SystemWallerService::getInstance()->increaseBalance((float)$fee->value);
                $maxLevel = LevelCondition::query()->max('level');
                switch ($agencyStatus) {
                    case User::AGENCY_STATUS_REGULAR:
                        Log::channel('agency')->info('User id ' . $user->id . ' mua đại lý thường.');
                        $this->handleUpdateNormalCommission($allParentNode, $maxLevel);
                        break;
                    case User::AGENCY_STATUS_VIP:
                        Log::channel('agency')->info('User id ' . $user->id . ' mua đại lý vip.');
                        $this->handleUpdateVipCommission($allParentNode, $maxLevel);
                        break;
                }//end switch
            }

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            throw new InputException(trans('response.not_found'));
        }//end try
    }

    /**
     * Handle update normal commission
     *
     * @param $allParentNode
     * @param $maxLevel
     */
    private function handleUpdateNormalCommission($allParentNode, $maxLevel)
    {
        foreach ($allParentNode as $key => $item) {
            $key++;
            if ($key > $maxLevel) break;
            $condition = NormalAgencyCondition::query()
                ->where('level', '=', $item->user->level)
                ->where('generation', '=', $key)
                ->first();
            if (!$condition || !$item->user) continue;

            $amount = (float)$condition->amount;
            Log::channel('agency')->info('Hoa hồng thường cho f' . $key . ' của user: $' . $amount);
            $this->updateCommission($item->user_id, $amount);
            $this->createHistoryCommission($item->user_id, $amount, HistoryCommission::TYPE_NORMAL_COMMISSION);
            SystemWallerService::getInstance()->decreaseBalance($amount);
            dispatch(new UpdateRankUserJob($item->user))->onQueue(config('queue.job_name.update_rank_by_volume_and_agency'));
        }//end foreach
    }

    /**
     * Handle update vip commission
     *
     * @param $allParentNode
     * @param $maxLevel
     */
    private function handleUpdateVipCommission($allParentNode, $maxLevel)
    {
        foreach ($allParentNode as $key => $item) {
            $key++;
            if ($key > $maxLevel) break;
            $condition = VipAgencyCondition::query()
                ->where('level', '=', $item->user->level)
                ->where('generation', '=', $key)
                ->first();
            if (!$condition || !$item->user) continue;

            $amount = (float)$condition->amount;
            Log::channel('agency')
                ->info('Hoa hồng vip cho f' . $key . ' cho user' . $item->user_id . ': $' . $amount);
            $this->updateCommission($item->user_id, $amount);
            $this->createHistoryCommission($item->user_id, $amount, HistoryCommission::TYPE_VIP_COMMISSION);
            SystemWallerService::getInstance()->decreaseBalance($amount);
            dispatch(new UpdateRankUserJob($item->user))->onQueue(config('queue.job_name.update_rank_by_volume_and_agency'));
        }//end foreach
    }

    /**
     * Update agency commission
     *
     * @param $userId
     * @param $amount
     */
    private function updateCommission($userId, $amount)
    {
        User::query()
            ->where('id', $userId)
            ->update([
                'usdt_balance' => DB::raw('`usdt_balance` + ' . $amount),
            ]);
    }

    /**
     * Create history commission
     *
     * @param $receiveUserId
     * @param $amount
     * @param $type
     */
    private function createHistoryCommission($receiveUserId, $amount, $type)
    {
        $user = $this->user;
        HistoryCommission::query()->create([
            'user_id' => $receiveUserId,
            'from_user_id' => $user->id,
            'value' => $amount,
            'type' => $type,
        ]);
    }
}
