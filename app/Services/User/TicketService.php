<?php

namespace App\Services\User;

use App\Exceptions\InputException;
use App\Models\LuckyWheel;
use App\Models\Setting;
use App\Models\TicketHistory;
use App\Models\User;
use App\Services\Common\SystemWallerService;
use App\Services\Service;
use App\Services\Traits\PaginationTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketService extends Service
{
    use PaginationTrait;

    /**
     * Buy ticket
     *
     * @param $total
     * @return bool
     * @throws InputException
     */
    public function buyTicket($total): bool
    {
        $user = $this->user;
        if (!$total) {
            throw new InputException(trans('response.invalid'));
        }//end if

        $ticketPrice = $this->getTicketPrice();
        $totalPrice = (int)$total * (float)$ticketPrice;
        if ($user->usdt_balance < $totalPrice) {
            throw new InputException(trans('response.not_enough'));
        }//end if

        DB::beginTransaction();
        try {
            $updateBalance = User::query()
                ->where('id', $user->id)
                ->where('usdt_balance', '>=', $totalPrice)
                ->update([
                    'total_tickets' => DB::raw("total_tickets + " . intval($total)),
                    'usdt_balance' => DB::raw("usdt_balance - " . floatval($totalPrice)),
                ]);
            if ($updateBalance <= 0) {
                throw new InputException(trans('response.not_enough'));
            }

            SystemWallerService::getInstance()->increaseBalance($totalPrice);
            $this->createTicketHistory($user->id, TicketHistory::TYPE_BUY, (int)$total);

            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollback();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Get ticket price
     *
     * @return mixed
     */
    public function getTicketPrice(): mixed
    {
        $listKeys = ['ticket_price', 'ticket_price_golden_hour', 'start_golden_hour', 'end_golden_hour'];
        $settings = Setting::query()
            ->whereIn('key', $listKeys)
            ->get()
            ->keyBy('key');

        $ticketPrice = $settings->get('ticket_price')?->value;
        $ticketPriceGoldenHour = $settings->get('ticket_price_golden_hour')?->value;

        if ($settings->has('start_golden_hour') && $settings->has('end_golden_hour')) {
            $beginGoldenHour = Carbon::parse($settings->get('start_golden_hour')->value);
            $endGoldenHour = Carbon::parse($settings->get('end_golden_hour')->value);

            if (Carbon::now()->between($beginGoldenHour, $endGoldenHour)) {
                return $ticketPriceGoldenHour;
            }//end if
        }//end if

        return $ticketPrice;
    }

    /**
     * Spin wheel
     *
     * @return false|Builder|Builder[]|Collection|Model|null
     * @throws InputException
     */
    public function spinWheel()
    {
        Log::channel('spin_wheel')->info('=============================================================');
        $user = $this->user;
        $luckyWheelExist = LuckyWheel::query()->where('prize_quantity', '>', 0)->exists();
        if (!$luckyWheelExist) {
            throw new InputException(trans('response.invalid'));
        }//end if

        if ($user->total_tickets < 1) {
            Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' - không có vé quay số.');
            throw new InputException(trans('response.no_ticket'));
        }//end if

        DB::beginTransaction();
        try {
            Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' bắt đầu quay số, số vé ban đầu . ' . $user->total_tickets);
            // Cập nhật số vé
            $this->reduceUserTicket($user->id);

            // Random vé
            $prize = $this->getRandomPrize();
            switch ($prize->spin_again) {
                case LuckyWheel::SPIN_AGAIN:
                    Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' trúng ô quay lại');
                    $this->createTicketHistory($user->id, TicketHistory::TYPE_REFUND, 1, $prize['name_vi'], $prize['reward'], $prize['id']);
                    $this->increaseUserTicket($user->id);
                    break;
                case LuckyWheel::NO_SPIN_AGAIN:
                    $nameVi = $prize->name_vi;
                    $this->createTicketHistory($user->id, TicketHistory::TYPE_USE, 1, $prize['name_vi'], $prize['reward'], $prize['id']);

                    if ($prize->id != LuckyWheel::MISS_A_TURN) {
                        Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' trúng ' . $nameVi);
                        UserService::getInstance()->increaseUsdtBalance($user->id, $prize->reward);
                        SystemWallerService::getInstance()->decreaseBalance((float)$prize->reward);
                    } else {
                        Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' quay vào ' . $nameVi);
                    }//end if

                    break;
            }//end switch

            DB::commit();
            Log::channel('spin_wheel')->info('UserId: ' . $user->id . ' kết thúc quay số');

            return $prize;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), [$exception]);

            return false;
        }//end try
    }

    /**
     * Get random prize
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getRandomPrize()
    {
        $result = null;
        $weights = [];
        $probabilities = LuckyWheel::query()->where('prize_quantity', '>', 0)->get();

        foreach ($probabilities as $probability) {
            $weights[$probability->id] = ($probability->prize_quantity / 100 * $probability->winning_probability * config('user.spin_probability'));
        }//end foreach

        $totalPercent = array_sum($weights);
        $randomNumber = mt_rand(1, (int)$totalPercent);
        $cumulative = 0;

        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($randomNumber <= $cumulative) {
                $prizeRecord = LuckyWheel::query()->find($key);
                if ($prizeRecord->prize_quantity > 0) {
                    $prizeRecord->decrement('prize_quantity');
                    $result = $prizeRecord;
                    break;
                } else {
                    return $this->getRandomPrize();
                }//end if
            }//end if
        }//end foreach

        return $result;
    }

    /**
     * Create ticket history
     *
     * @param $userId
     * @param int $type
     * @param $quantity
     * @param $prize
     * @param $value
     */
    public function createTicketHistory($userId, int $type = TicketHistory::TYPE_BUY, $quantity = null, $prize = null, $value = null, $luckyWheelId = null)
    {
        TicketHistory::query()->create([
            'user_id' => $userId,
            'quantity' => $quantity,
            'prize' => $prize,
            'value' => $value,
            'type' => $type,
            'lucky_wheel_id' => $luckyWheelId,
        ]);
    }

    /**
     * Reduce ticket
     *
     * @param $userId
     * @param int $ticket
     */
    public function reduceUserTicket($userId, int $ticket = 1)
    {
        $updateTotalTicket = User::query()
            ->where('id', $userId)
            ->where('total_tickets', '>=', $ticket)
            ->update([
                'total_tickets' => DB::raw('`total_tickets` - ' . $ticket),
            ]);
        if ($updateTotalTicket <= 0) {
            throw new InputException(trans('response.no_ticket'));
        }
    }

    /**
     * Increase user ticket
     *
     * @param $userId
     * @param int $ticket
     */
    public function increaseUserTicket($userId, int $ticket = 1)
    {
        User::query()
            ->where('id', $userId)
            ->update([
                'total_tickets' => DB::raw('`total_tickets` + ' . $ticket),
            ]);
    }

    /**
     * History
     *
     * @param $page
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function history($page, $perPage): LengthAwarePaginator
    {
        $user = $this->user;
        $data = TicketHistory::query()
            ->where('user_id', $user->id)
            ->where('type', '<>', TicketHistory::TYPE_BUY)
            ->orderByDesc('updated_at')
            ->get();

        return $this->paginate($data, $perPage, $page);
    }
}
