<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date
     *
     * @param $date
     * @return string|null
     */
    public static function formatDate($date): ?string
    {
        if (!$date) {
            return null;
        }//end if

        return Carbon::parse($date)->format(config('date.fe_date_format'));
    }

    /**
     * Format datetime
     *
     * @param $dateTime
     * @return string|null
     */
    public static function formatDateTime($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }//end if

        return Carbon::parse($dateTime)->format(config('date.fe_date_time_format'));
    }

    /**
     * Format datetime
     *
     * @param $dateTime
     * @return string|null
     */
    public static function formatDateTimeFull($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }//end if

        return Carbon::parse($dateTime)->format(config('date.fe_date_time_full_format'));
    }

    /**
     * Format datetime japan
     *
     * @param $dateTime
     * @return string|null
     */
    public static function formatDateTimeJa($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }//end if

        return Carbon::parse($dateTime)->format(config('date.fe_date_time_ja_format'));
    }

    /**
     * Format date japan
     *
     * @param $dateTime
     * @return string|null
     */
    public static function formatDateJa($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }//end if

        return Carbon::parse($dateTime)->format(config('date.fe_date_ja_format'));
    }

    /**
     * Format date time half Japan
     *
     * @param $dateTime
     * @return string|null
     */
    public static function formatDateTimeHalfJa($dateTime): ?string
    {
        if (!$dateTime) {
            return null;
        }//end if

        return Carbon::parse($dateTime)->format(config('date.fe_date_time_half_ja_format'));
    }

    /**
     * @param $hour
     * @return string|null
     */
    public static function formatHour($hour): ?string
    {
        if (empty($hour)) {
            return null;
        }//end if

        return $hour . trans('user.fe_hour_format');
    }
    /**
     * @param $timestamp
     * 
     * return string|null
     */
    public static function formatDateTimeFromTimeStamp($timestamp): ?string
    {
        if (!$timestamp) {
            return null;
        }

        return Carbon::createFromTimestamp($timestamp)->format(config('date.fe_date_time_format'));
    } 
}
