<?php

namespace App\Services\User;

use App\Exceptions\InputException;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Service;
use App\Services\Traits\PaginationTrait;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService extends Service
{
    use PaginationTrait;

    /**
     * Internal deposit money form usdt balance to real balance
     *
     * @throws InputException
     */
    public function internalDepositMoney($amount): bool
    {
        $user = $this->user;
        if ($user->usdt_balance <= 0 || $user->usdt_balance < $amount) {
            throw new InputException(trans('response.transaction.invalid'));
        }//end if

        DB::beginTransaction();
        try {
            $updateBalance = User::query()
                ->where('id', $this->user->id)
                ->where('usdt_balance', '>=', $amount)
                ->update([
                    'real_balance' => DB::raw('real_balance + ' . $amount),
                    'usdt_balance' => DB::raw('usdt_balance - ' . $amount),
                ]);
            if ($updateBalance <= 0) {
                throw new InputException('response.transaction.invalid');
            }

            TransactionDetail::query()->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT,
                'status' => TransactionDetail::TRANSACTION_STATUS_COMPLETED,
            ]);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Internal withdraw money form real balance to usdt balance
     *
     * @param $amount
     * @return bool
     * @throws InputException
     */
    public function internalWithdrawMoney($amount): bool
    {
        $user = $this->user;
        if ($user->real_balance <= 0 || $user->real_balance < $amount) {
            throw new InputException(trans('response.transaction.invalid'));
        }//end if

        DB::beginTransaction();
        try {
            $updateBalance = User::query()
                ->where('id', $this->user->id)
                ->where('real_balance', '>=', $amount)
                ->update([
                    'real_balance' => DB::raw('real_balance - ' . $amount),
                    'usdt_balance' => DB::raw('usdt_balance + ' . $amount),
                ]);
            if ($updateBalance <= 0) {
                throw new InputException('response.transaction.invalid');
            }

            TransactionDetail::query()->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW,
                'status' => TransactionDetail::TRANSACTION_STATUS_COMPLETED,
            ]);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Withdraw transaction
     *
     * @param $data
     * @return bool
     * @throws InputException
     */
    public function withdrawTransaction($data): bool
    {
        $user = $this->user;
        $checkUserSetting = UserSetting::query()->where('user_id', $user->id)->first();
        if ($checkUserSetting && $checkUserSetting->is_marketing == UserSetting::MARKETING) {
            throw new InputException(trans('transaction.cannot_withdraw'));
        }//end if

        $totalMoney = (float)$data['amount'] + (float)config('user.transaction.fee');
        if ($user->usdt_balance <= 0 || $user->usdt_balance < $totalMoney) {
            throw new InputException(trans('response.transaction.invalid'));
        }//end if

        DB::beginTransaction();
        try {
            $updateBalance = User::query()->where('id', $user->id)
                ->where('usdt_balance', '>=', $totalMoney)
                ->decrement('usdt_balance', $totalMoney);
            if ($updateBalance <= 0) {
                throw new InputException(trans('response.transaction.invalid'));
            }

            TransactionDetail::query()->create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'address' => $data['address'],
                'note' => $data['note'],
                'type' => TransactionDetail::TRANSACTION_TYPE_WITHDRAW,
                'status' => TransactionDetail::TRANSACTION_STATUS_PENDING,
            ]);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * History
     *
     * @param $page
     * @param $perPage
     * @param null $type
     * @return LengthAwarePaginator
     */
    public function history($page, $perPage, $type = null): LengthAwarePaginator
    {
        $user = $this->user;
        $typesTransaction = match ((int)$type) {
            TransactionDetail::TRANSACTION_TYPE_DEPOSIT => [TransactionDetail::TRANSACTION_TYPE_DEPOSIT],
            TransactionDetail::TRANSACTION_TYPE_WITHDRAW => [TransactionDetail::TRANSACTION_TYPE_WITHDRAW],
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => [TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT],
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => [TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW],
            TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => [TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL],
            TransactionDetail::TRANSACTION_TYPE_BUY_VIP => [TransactionDetail::TRANSACTION_TYPE_BUY_VIP],
            default => TransactionDetail::TRANSACTION_TYPE,
        };

        $data = TransactionDetail::query()
            ->where('user_id', $user->id)
            ->whereIn('type', $typesTransaction)
            ->orderByDesc('updated_at')
            ->get();

        return $this->paginate($data, $perPage, $page);
    }

    /**
     * Agency transaction
     *
     * @param $userId
     * @param $amount
     * @param $type
     * @return void
     */
    public function agencyTransaction($userId, $amount, $type)
    {
        TransactionDetail::query()->create([
            'user_id' => $userId,
            'amount' => $amount,
            'type' => $type,
            'status' => TransactionDetail::TRANSACTION_STATUS_COMPLETED,
        ]);
    }
}
