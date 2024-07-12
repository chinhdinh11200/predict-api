<?php

namespace App\Helpers;

class NumberHelper
{
    private const ADMIN_FORMAT_DECIMAL = 4;
    /**
     * Format money
     *
     * @param $money
     * @return string
     */
    public static function formatMoney($money): string
    {
        $money = floatval($money);
        $formatMoney = number_format($money, 2, '.', ',');
        $formatMoney = rtrim(rtrim($formatMoney, '0'), '.');

        return '￥' . $formatMoney;
    }

    /**
     * Generate Numeric OTP
     *
     * @param $length
     * @return string|null
     */
    public static function generateNumericOTP($length): ?string
    {
        $result = null;
        for ($i = 1; $i <= $length; $i++) {
            $result .= mt_rand(0, 9);
        }//end for

        return $result;
    }

    public static function admin_number_format_no_zero($value, $decimal = self::ADMIN_FORMAT_DECIMAL)
    {
        if (!$value) {
            return 0;
        }
        return number_format($value, $decimal);
    }
}
