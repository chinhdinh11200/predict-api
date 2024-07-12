<?php

namespace App\Admin\Repositories;

use App\Models\Bet;
use App\Models\HistoryCommandBet;
use App\Models\LastResult;
use App\Models\User;
use App\Services\User\BetService;
use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\EloquentRepository;

class RedisUserBetBuy extends EloquentRepository
{
    protected $eloquentClass = User::class;
    private static $sessionId = null;
    private static $buy = 0;
    private static $historyCommandBet = null;
    private static $numberOfBetByType = [];
    private static $userIds = [];

    public static function getBetsWithType($userId)
    {
        return self::$numberOfBetByType[$userId] ?? 0;
    }

    public static function getAll()
    {
        try {
            $time = time() * 1000;
            $session = LastResult::query()
                ->where('start_time', '<=', $time)
                ->where('end_time', '>', $time)
                ->first();

            if ($session->is_bet_session == LastResult::BET) {
                $sessionId = $session->id + 1;
            } else {
                $sessionId = $session->id;
            }
            self::$sessionId = $sessionId;
            self::$historyCommandBet = HistoryCommandBet::query()->where('session_id', $sessionId)->first();
            if (request()->get('end')) {
                return [];
            }
            $bets = Bet::query()
                ->select('user_id')
                ->selectRaw("COUNT(CASE WHEN bet_type > 0 THEN bet_type END) as 'buy_type'")
                ->selectRaw("SUM(CASE WHEN bet_type > 0 THEN amount END) as 'buy'")
                ->selectRaw("SUM(CASE WHEN bet_type <= 0 THEN amount END) as 'sell'")
                ->selectRaw("AVG(bet_type) as 'bet_type'")
                ->where('session_id', $sessionId)
                ->where('is_demo', Bet::REAL_TYPE)
                ->groupBy('user_id')
                ->having('buy_type', '>', 0)
                ->get();
            foreach ($bets as $bet) {
                self::$buy += $bet->buy;
                self::$numberOfBetByType[$bet->user_id] = $bet->buy;
                self::$userIds[] = $bet->user_id;
            }

            return self::$userIds;
        } catch (\Exception $e) {
            logger($e->getMessage(), [$e]);
            return self::$userIds;
        }
    }

    public static function getTotalAmountInSession()
    {
        return [
            'buy' => self::$buy,
            'sessionId' => self::$sessionId,
            'historyCommandBet' => self::$historyCommandBet,
        ];
    }

    public function get(Grid\Model $model)
    {
        $this->setSort($model);
        $this->setPaginate($model);

        $query = $this->newQuery();

        if ($this->relations) {
            $query->with($this->relations);
        }

        $query->whereIn('id', self::$userIds);

        return $model->apply($query, true, $this->getGridColumns());
    }
}
