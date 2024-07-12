<?php

namespace App\Services\User;

use App\Models\HistoryCommission;
use App\Services\Service;
use App\Services\Traits\PaginationTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class HistoryCommissionService extends Service
{
    use PaginationTrait;

    /**
     * History
     * 
     * @param $page
     * @param $perPage
     * @param null $type
     * $return LengthAwarePaginator
     */
    public function history($page, $perPage, $type = null): LengthAwarePaginator
    {
        $user = $this->user;
        $typeCommission = match ((int) $type) {
            1 => [HistoryCommission::TYPE_BET_COMMISSION],
            2 => [HistoryCommission::TYPE_VIP_COMMISSION],
            3 => [HistoryCommission::TYPE_NORMAL_COMMISSION],
            default => HistoryCommission::COMMISSION_TYPE
        };

        $data = HistoryCommission::query()
            ->with('fromUser')
            ->where('user_id', $user->id)
            ->whereIn('type', $typeCommission)
            ->orderByDesc('updated_at')
            ->get();

        return $this->paginate($data, $perPage, $page);
    }
}
