<?php

namespace App\Services\User;

use App\Exceptions\InputException;
use App\Jobs\PayCommissionForUserJob;
use App\Jobs\UpdateRankUserJob;
use App\Models\AgencyCondition;
use App\Models\Bet;
use App\Models\Chart;
use App\Models\HistoryPool;
use App\Models\LastResult;
use App\Models\LevelCondition;
use App\Models\HistoryCommission;
use App\Models\Pool;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserBlockBet;
use App\Models\UserRelationship;
use App\Models\UserSetting;
use App\Services\Service;
use App\Services\Traits\PaginationTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BetService extends Service
{
    use PaginationTrait;

    /**
     * Bet
     *
     * @param $data
     * @return void
     * @throws InputException
     */
    public function bet($data)
    {
        $userSetting = $this->user->setting;
        $session = $this->getSession();
        $amount = floatval($data['amount']);
        $isDemo = $data['is_demo'];
        $maintain = config('app.maintain');
        if ($maintain) {
            throw new InputException(trans('bet.bet_maintain'));
        }
        if ($session) {
            $checkBetBlockSession = UserBlockBet::query()
                ->where('user_id', $this->user->id)
                ->where('session_id', $session->id)
                ->first();
            if ($checkBetBlockSession) {
                throw new InputException(trans('bet.bet_fail'));
            }//end if

            $setting = Setting::query()->where('key', 'trade_min')->first();
            if ($setting && $amount < $setting->value) {
                throw new InputException(trans('bet.bet_minimum', ['amount' => $setting->value]));
            }

            $checkBetSession = Bet::query()
                ->where('session_id', '=', $session->id + 1)
                ->where('user_id', $this->user->id)
                ->first();
            if ($checkBetSession) {
                if ($checkBetSession->bet_type != (int)$data['bet_type']) throw new InputException(trans('bet.bet_fail_oneway'));
                if ($checkBetSession->is_demo != $isDemo) throw new InputException(trans('bet.bet_fail_demo'));
            } //end if

            DB::beginTransaction();
            try {
                if ($isDemo) {
                    $updateBalance = User::query()
                        ->where('id', $this->user->id)
                        ->where('virtual_balance', '>=', $amount)
                        ->decrement('virtual_balance', $amount);
                    if ($updateBalance <= 0) {
                        throw new InputException(trans('bet.not_enough'));
                    }
                } else {
                    $updateBalance = User::query()
                        ->where('id', $this->user->id)
                        ->where('real_balance', '>=', $amount)
                        ->decrement('real_balance', $amount);

                    if ($updateBalance <= 0) {
                        throw new InputException(trans('bet.not_enough'));
                    }
                } //end if

                //Tạo bet
                $bet = Bet::create([
                    'user_id' => $this->user->id,
                    'session_id' => $session->id + 1,
                    'amount' => $amount,
                    'is_demo' => $isDemo,
                    'bet_type' => $data['bet_type'],
                    'is_marketing' => $userSetting->is_marketing,
                ]);

                if (!$isDemo && $userSetting->is_marketing == UserSetting::NONE_MARKETING) {
                    //Cập nhật pool và lịch sử
                    Pool::query()
                        ->where('id', 1)
                        ->update([
                            'value' => DB::raw('`value` + ' . $amount),
                        ]);

                    HistoryPool::create([
                        'user_id' => $this->user->id,
                        'bet_id' => $bet->id,
                        'value' => $amount,
                        'type' => HistoryPool::TYPE_BET
                    ]);
                } //end if

                if (!$isDemo && $userSetting->is_marketing == UserSetting::NONE_MARKETING) {
                    // TODO: kiểm tra điều kiện nhận volumn của user
                    // kiểm tra level cho user dựa vào chính sách
                    dispatch(new UpdateRankUserJob($this->user))->onQueue(config('queue.job_name.update_rank_by_volume_and_agency'))->afterCommit();
                    dispatch(new PayCommissionForUserJob($this->user, $bet))->onQueue(config('queue.job_name.pay_commission_for_user'))->afterCommit();
                    // TODO: Volumn thưởng vé quay
                }
                DB::commit();

                return $bet;
            } catch (Exception $exception) {
                DB::rollBack();
                logger($exception->getMessage());
                throw new InputException($exception->getMessage());
            } //end try
        } else {
            throw new InputException(trans('bet.not_bet_time'));
        } //end if
    }

    /**
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getSession()
    {
        $time = time() * 1000;
        return LastResult::query()
            ->where('start_time', '<', $time)
            ->where('end_time', '>=', $time)
            ->where('is_bet_session', LastResult::BET)
            ->first();
    }

    /**
     * History
     *
     * @param $page
     * @param $perPage
     * @param $startTime
     * @param $endTime
     * @param $type
     *
     * @return LengthAwarePaginator
     */
    public function history($page, $perPage, $startTime, $endTime, $isResult): LengthAwarePaginator
    {
        $typeResult = match ((int)$isResult) {
            0 => Bet::NO_ACTION,
            1 => Bet::EXECUTED_RESULT,
            default => Bet::NO_ACTION,
        };

        $query = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_result', $typeResult);
        if ($startTime && $endTime) {
            $query->whereBetween('updated_at', [Carbon::parse($startTime)->startOfDay(), Carbon::parse($endTime)->endOfDay()]);
        } elseif (!$startTime) {
            $query->where('updated_at', '<=', Carbon::parse($endTime)->endOfDay());
        }
        $data = $query->orderByDesc('updated_at')->get();

        return $this->paginate($data, $perPage, $page);
    }

    /**
     * Volume of current week
     *
     * @param User $user
     *
     * @return int
     */
    public function getVolumeCurrentWeek(User $user)
    {
        if (!$user) {
            return 0;
        }
        $startWeek = Carbon::now()->startOfWeek();
        $now = Carbon::now()->endOfDay();
        $total = Bet::query()
            ->where('user_id', $user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->whereBetween('created_at', [$startWeek, $now])
            ->sum('amount');

        return $total;
    }

    /**
     * Count child agency user has agency status not equal User::AGENCY_STATUS_NON
     *
     * @param UserRelationShip $userRelationship
     */
    public function countChildAgencyUserNotNone(UserRelationShip $userRelationship): int
    {
        return $userRelationship->children()->whereHas('user', function ($queryUser) {
            $queryUser->where('agency_status', '!=', User::AGENCY_STATUS_NON);
        })->count();
    }

    /**
     * Check rank by volume of week and child agency
     * get min rank by volume and rank by child agency
     *
     * @param $volumeOfWeek
     * @param $numberChildAgency
     * $return int
     */
    public function getRankByVolumeAndAgency($volumeOfWeek, $numberChildAgency): int
    {
        $maxLevelByVolume = LevelCondition::query()
            ->where('volume_week', '<=', $volumeOfWeek)
            ->orderBy('level', 'DESC')
            ->first();
        if (!$maxLevelByVolume) return 0;
        if ($maxLevelByVolume->condition_f1 >= $numberChildAgency) {
            $maxLevelByChileAgency = LevelCondition::query()
                ->where('condition_f1', $numberChildAgency)
                ->first();

            return $maxLevelByChileAgency->level ?? 0;
        }

        return $maxLevelByVolume->level;
    }

    /**
     *
     * @param User $user
     * @param Bet $bet
     */
    public function payCommissionForUser(User $user, Bet $bet)
    {
        $maxLevel = LevelCondition::query()->max('level');
        $userRelationship = UserRelationship::query()->where('user_id', $user->id)->first();
        $parents = $userRelationship->ancestors()->with('user')->orderByDesc('parent_id')->get();
        foreach ($parents as $f => $parent) {
            if ($f + 1 > $maxLevel) break;
            $userCommission = AgencyCondition::query()
                ->where('level', $parent->user->level)
                ->where('generation', $f + 1)
                ->first();
            if (!$userCommission || !$parent->user) continue;

            // cộng hoa hồng
            if ($bet->is_demo == Bet::DEMO_TYPE) {
                // cộng hoa hồng ảo
                User::query()
                    ->where('id', $parent->user_id)
                    ->update([
                        'virtual_balance' => $parent->user->virtual_balance + $bet->amount * $userCommission->percent / 100,
                    ]);
            } else {
                // cộng hoa hồng thật
                User::query()
                    ->where('id', $parent->user_id)
                    ->update([
                        'real_balance' => $parent->user->real_balance + $bet->amount * $userCommission->percent / 100,
                    ]);
                // tạo commission history type = 1;
                HistoryCommission::query()->create([
                    'user_id' => $parent->user_id,
                    'from_user_id' => $user->id,
                    'value' => $bet->amount * $userCommission->percent / 100,
                    'type' => HistoryCommission::TYPE_BET_COMMISSION,
                ]);
            } // end if
        }
    }

    /**
     * History
     *
     * @param $page
     * @param $perPage
     * @param $startTime
     * @param $endTime
     * @param $type
     *
     * @return LengthAwarePaginator
     */
    public function tradeHistory($page, $perPage, $startTime, $endTime): LengthAwarePaginator
    {
        $query = Bet::query()
            ->where('user_id', $this->user->id);

        if ($startTime && $endTime) {
            $query->whereBetween('updated_at', [Carbon::parse($startTime)->startOfDay(), Carbon::parse($endTime)->endOfDay()]);
        } elseif (!$startTime) {
            $query->where('updated_at', '<=', Carbon::parse($endTime)->endOfDay());
        }
        $data = $query->orderByDesc('updated_at')->get();

        return $this->paginate($data, $perPage, $page);
    }
    /**
     * Update user balance after bet
     *
     * @param $sessionId
     * @return array|bool
     */
    public function updateUserBalance($sessionId)
    {
        $user = $this->user;
        $query = Bet::query()
            // ->with('')
            ->where('user_id', $user->id)
            ->where('is_result', Bet::NO_ACTION);
        $totalProfitBetReal = 0;
        $totalProfitBetDemo = 0;
        if ($sessionId) {
            $chart = Chart::query()->where('session_id', $sessionId)->first();
            if (!$chart) {
                return [
                    'total_profit_bet_demo' => $totalProfitBetDemo,
                    'total_profit_bet_real' => $totalProfitBetReal,
                ];
            } //end if

            $query = $query->where('session_id', $sessionId);
        } //end if

        $query = $query->get();
        if ($query->isEmpty()) {
            return [
                'total_profit_bet_demo' => $totalProfitBetDemo,
                'total_profit_bet_real' => $totalProfitBetReal,
            ];
        } //end if

        DB::beginTransaction();
        try {
            $profitBetSetting = Setting::query()->where('key', 'profit_bet')->first();
            $profitBetRate = $profitBetSetting->value ?? 0;
            foreach ($query as $item) {
                $chart = Chart::query()->where('session_id', $item->session_id)->first();
                $upOrDown = $chart->open_price > $chart->close_price ? Bet::DOWN : Bet::UP;
                $checkWinOrLose = $item->bet_type == $upOrDown;
                if (!$checkWinOrLose) {
                    $item->update([
                        'is_result' => Bet::EXECUTED_RESULT,
                        'result' => Bet::LOSE,
                        'type' => Bet::SUB,
                        'reward' => 0,
                    ]);
                    continue;
                } //end if

                // win
                $amountBet = $item->amount;
                $profitBet = $amountBet + $amountBet * $profitBetRate / 100;
                $item->update([
                    'is_result' => Bet::EXECUTED_RESULT,
                    'result' => Bet::WIN,
                    'type' => Bet::ADD,
                    'reward' => $profitBet,
                ]);

                if ($item->is_demo == Bet::DEMO_TYPE) {
                    $totalProfitBetDemo += $profitBet;
                } else {
                    $totalProfitBetReal += $profitBet;
                } //end if
            } //end foreach

            User::query()
                ->where('id', $user->id)
                ->update([
                    'real_balance' => DB::raw('`real_balance` + ' . $totalProfitBetReal),
                    'virtual_balance' => DB::raw('`virtual_balance` + ' . $totalProfitBetDemo),
                ]);
            DB::commit();

            return [
                'total_profit_bet_demo' => $totalProfitBetDemo,
                'total_profit_bet_real' => $totalProfitBetReal,
            ];
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        } //end try
    }
}
