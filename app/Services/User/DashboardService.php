<?php

namespace App\Services\User;

use App\Models\Bet;
use App\Models\TicketHistory;
use App\Models\HistoryCommission;
use App\Services\Service;

class DashboardService extends Service
{

    /**
     * Dashboard
     *
     * @return array
     */
    public function dashboard(): array
    {
        $netProfit = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->sum('amount') - Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::ADD)
            ->sum('reward') - Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::ADD)
            ->sum('amount');
        $totalTrade = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->count();
        $totalTradeAmount = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->sum('amount');
        $totalWinRound = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('result', BET::WIN)
            ->count();
        $totalLoseRound = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('result', BET::LOSE)
            ->count();
        $totalSell = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('bet_type', Bet::DOWN)
            ->count();
        $totalBuy = Bet::query()
            ->where('user_id', $this->user->id)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('bet_type', Bet::UP)
            ->count();
        $ticketPrizeAmount = TicketHistory::query()
            ->where('user_id', $this->user->id)
            ->where('type', TicketHistory::TYPE_USE)
            ->sum('value');
        $commission = HistoryCommission::query()
            ->where('user_id', $this->user->id)
            ->sum('value');
        $revenue = $ticketPrizeAmount + $commission;

        return [
            'net_profit' => $netProfit,
            'revenue' => $revenue,
            'total_trade' => $totalTrade,
            'total_trade_amount' => $totalTradeAmount,
            'total_win_round' => $totalWinRound,
            'total_lose_round' => $totalLoseRound,
            'win_rate' => $totalTrade > 0 ? $totalWinRound / $totalTrade * 100 : 0,
            'total_sell' => $totalTrade > 0 ? $totalSell / $totalTrade * 100 : 0,
            'total_buy' => $totalTrade > 0 ? $totalBuy / $totalTrade * 100 : 0,
        ];
    }
}
