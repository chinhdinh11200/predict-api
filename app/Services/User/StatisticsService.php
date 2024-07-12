<?php

namespace App\Services\User;

use App\Models\HistoryCommission;
use App\Models\TicketHistory;
use App\Services\Service;

class StatisticsService extends Service
{
    /**
     * Get statistics
     *
     * @return array
     */
    public function index(): array
    {
        $user = $this->user;
        $ticketPrizeAmount = TicketHistory::query()
            ->where('user_id', $user->id)
            ->where('type', TicketHistory::TYPE_USE)
            ->sum('value');
        $commission = HistoryCommission::query()
            ->where('user_id', $user->id)
            ->sum('value');
        // TODO: Doanh thu nhận được Hoa hồng/Volume của tuyến dưới

        return [
            'revenue' => $ticketPrizeAmount + $commission,
//            'net_profit' => 0,
        ];
    }
}
