<?php

namespace App\Helpers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class LuckyWheelHelper
{
    /**
     * Get prize type
     *
     * @param $id
     * @return Repository|Application|mixed
     */
    public static function getPrizeType($id): mixed
    {
        return match ((int) $id) {
            1 => config('lucky-wheel.type.miss'),
            2 => config('lucky-wheel.type.spin_again'),
            10 => config('lucky-wheel.type.jackpot'),
            default => config('lucky-wheel.type.money'),
        };
    }

    /**
     * Convert int to currency text
     *
     * @param $prize
     * @return string
     */
    public static function getPrizeFormat($prize): string
    {
        return $prize > 0 ? '+$' . number_format($prize) : '';
    }

}
