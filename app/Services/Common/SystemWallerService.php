<?php

namespace App\Services\Common;

use App\Models\SystemWallet;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class SystemWallerService extends Service
{
    /**
     * Increase system wallet
     *
     * @param $amount
     * @return void
     */
    public function increaseBalance($amount)
    {
        SystemWallet::query()
            ->where('id', 1)
            ->update([
                'value' => DB::raw('`value` + ' . $amount),
            ]);
    }

    /**
     * Decrease system wallet
     *
     * @param $amount
     * @return void
     */
    public function decreaseBalance($amount)
    {
        SystemWallet::query()
            ->where('id', 1)
            ->update([
                'value' => DB::raw('`value` - ' . $amount),
            ]);
    }
}
