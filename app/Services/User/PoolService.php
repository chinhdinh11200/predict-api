<?php

namespace App\Services\User;

use App\Models\HistoryPool;
use App\Services\Service;
use App\Services\Traits\PaginationTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class PoolService extends Service
{
    use PaginationTrait;

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
        $typePool = match ((int)$type) {
            1 => [HistoryPool::TYPE_BET],
            2 => [HistoryPool::TYPE_PAY],
            default => HistoryPool::TYPE,
        };
        $data = HistoryPool::query()
            ->where('user_id', $user->id)
            ->whereIn('type', $typePool)
            ->orderByDesc('updated_at')
            ->get();

        return $this->paginate($data, $perPage, $page);
    }
}
