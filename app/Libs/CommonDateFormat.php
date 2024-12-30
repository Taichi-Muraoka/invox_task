<?php

namespace App\Libs;

use Illuminate\Support\Carbon;
use Closure;

/**
 * 日付フォーマットの共通処理
 */
class CommonDateFormat
{
    /**
     * 日付を曜日ありでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(YYYY/MM/DD(曜日))
     */
    public static function formatYmdDay($dateData = null)
    {
        $targetDate = null;
        if (isset($dateData)) {
            $targetDate = Carbon::parse($dateData);
        } else {
            $targetDate = Carbon::now();
        }

        return Carbon::parse($targetDate)->isoFormat('YYYY/MM/DD(ddd)');
    }

    /**
     * 日付を曜日ありでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(YYYY年MM月DD日(曜日))
     */
    public static function formatYmdDayString($dateData = null)
    {
        $targetDate = null;
        if (isset($dateData)) {
            $targetDate = Carbon::parse($dateData);
        } else {
            $targetDate = Carbon::now();
        }

        return Carbon::parse($targetDate)->isoFormat('YYYY年MM月DD日(ddd)');
    }

    /**
     * 日付を曜日ありでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(MM月DD日(曜日))
     */
    public static function formatMdDayString($dateData = null)
    {
        $targetDate = null;
        if (isset($dateData)) {
            $targetDate = Carbon::parse($dateData);
        } else {
            $targetDate = Carbon::now();
        }

        return Carbon::parse($targetDate)->isoFormat('MM月DD日(ddd)');
    }

    /**
     * 日付をdd/mm/yyyyでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(DD/MM/YYYY)
     */
    public static function formatDmy($dateData = null)
    {
        if (isset($dateData)) {
            return Carbon::parse($dateData)->isoFormat('DD/MM/YYYY');
        } else {
            return;
        }
    }

    /**
     * 日付をdd/mm/yyyy HH:mmでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(DD/MM/YYYY HH:mm)
     */
    public static function formatDmyHm($dateData = null)
    {
        if (isset($dateData)) {
            return Carbon::parse($dateData)->isoFormat('DD/MM/YYYY HH:mm');
        } else {
            return;
        }
    }

    /**
     * 日付をyyyy/mm/ddでフォーマット - datetimeをdateへ
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(YYYY/MM/DD)
     */
    public static function formatYmd($dateData = null)
    {
        if (isset($dateData)) {
            return Carbon::parse($dateData)->isoFormat('YYYY/MM/DD');
        } else {
            return;
        }
    }

    /**
     * 時刻をHH:mmでフォーマット
     * @param   $dateData 日付
     * @return  フォーマット後の文字列(HH:mm)
     */
    public static function formatHm($dateData = null)
    {
        if (isset($dateData)) {
            return Carbon::parse($dateData)->isoFormat('HH:mm');
        } else {
            return;
        }
    }
}
