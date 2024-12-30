<?php

namespace App\Http\Controllers\Traits;

use DateTime;
use DateTimeZone;

/**
 * 日付 - コントローラ共通処理
 */
trait CtrlDateTrait
{
    //==========================
    // 関数名を区別するために
    // dt(日付)を先頭につける
    //==========================

    //------------------------------
    // 日付の取得
    //------------------------------

    /**
     * 指定されたタイムゾーンの現在日時を取得する（日時オブジェクト）
     *
     * @param string $locale タイムゾーン(指定なしの場合は Asia/Tokyo)
     * @return DateTime 日付
     */
    protected function dtGetTimezoneDateTime(String $locale = "Asia/Tokyo")
    {
        $localeDate = new DateTime('now', new DateTimeZone($locale));
        return $localeDate;
    }

    /**
     * 文字列の日時を、指定されたタイムゾーンの日時オブジェクトにする
     *
     * @param string $strdatetime 日時（文字列）
     * @param string $locale タイムゾーン(指定なしの場合は Asia/Tokyo)
     * @return DateTime 日付
     */
    protected function dtGetStr2TimezoneDateTime(String $strdatetime, String $locale = "Asia/Tokyo")
    {
        $localeDate = date_create($strdatetime, new DateTimeZone($locale));
        return $localeDate;
    }

    /**
     * 指定されたタイムゾーンの現在日時(または日付のみ)をフォーマット形式で取得する
     *
     * @param string $locale タイムゾーン
     * @param bool $dateOnly 日付のみならtrue, 日時ならfalse
     * @return string 日付の文字列
     */
    protected function dtGetTimezoneDateTimeFormat(String $locale = "Asia/Tokyo", $dateOnly = false)
    {
        $localeDate = new DateTime('now', new DateTimeZone($locale));

        if ($dateOnly) {
            return $this->dtFormatDate($localeDate);
        } else {
            return $this->dtFormatDateTime($localeDate);
        }
    }

    /**
     * 日時をフォーマット d/m/Y H:i:s形式
     *
     * @param DateTime $datetime 日時
     * @return string フォーマット後の日時
     */
    protected function dtFormatDateTime(DateTime $datetime)
    {
        return $datetime->format('d/m/Y H:i:s');
    }

    /**
     * 日をフォーマット d/m/Y形式
     *
     * @param DateTime $datetime 日時
     * @return string フォーマット後の日
     */
    protected function dtFormatDate(DateTime $datetime)
    {
        return $datetime->format('d/m/Y');
    }

    /**
     * タイムゾーンのプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function dtGetTimezoneList()
    {
        $list = [];
        foreach (DateTimeZone::listIdentifiers() as $val) {
            // date_default_timezone_set('UTC');
            // $utcTime = strtotime('2012-01-01 00:00:00');
            // date_default_timezone_set($val);
            // $date = date("Y/m/d H:i:s", $utcTime);
            // print $val."\t".$date."\n";

            $list[$val] = ["value" => $val];
        }

        return $list;
    }

    /**
     * 対象日付が指定範囲内かどうかをチェック
     *
     * @param $target_date
     * @param $from_date
     * @param $to_date
     * @return bool true:範囲内、false:範囲外
     */
    protected function dtCheckDateFromTo($target_date, $from_date, $to_date)
    {
        $targetDate = strtotime($target_date);

        if (
            $targetDate >= strtotime($from_date) &&
            $targetDate <= strtotime($to_date)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 日付を/区切りの形式にフォーマットする
     *
     * @param $target_date
     * @return string   YYYY/MM/DD形式
     */
    protected function dtFormatYmd($target_date)
    {
        if ($target_date == null || $target_date == '') {
            return '';
        } else {
            return date('Y/m/d', strtotime($target_date));
        }
    }

    /**
     * 時刻文字列がH:i形式かどうかをチェック
     *
     * @param $inputTime
     * @return bool
     */
    protected function dtCheckTimeFormat($inputTime)
    {
        // 入力文字列が正規表現パターンに一致するかチェック
        // ゼロなしでも許可とする。とりあえずコロン区切り
        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5]?[0-9]$/', $inputTime)) {
            // 一致した場合はtrueを返す
            return true;
        } else {
            // 一致しない場合はfalseを返す
            return false;
        }
    }

    /**
     * 分を時間に変換
     *
     * @param 時間(分)
     * @return 時間(時間)
     */
    protected function dtConversionTime($minutes)
    {
        $time = floor($minutes / 60 * 10) / 10;

        return $time;
    }

    /**
     * 分を時間に変換（少数第2位まで表示）
     *
     * @param $minutes 時間(分)
     * @return  時間(時間)
     */
    protected function dtConversionTimeDecimal($minutes)
    {
        $time = floor($minutes / 60 * 100) / 100;

        return $time;
    }

    /**
     * 分を時間に変換
     *
     * @param $minutes 時間(分)
     * @return string 時間(〇hour〇m)
     */
    public function dtConversionHourMinutes($minutes)
    {
        $conversion_hour = floor($minutes / 60 * 10) / 10;

        $hour = floor($conversion_hour);

        $minutes = floor($minutes % 60 * 10) / 10;

        return $hour . 'hour' . $minutes . 'm';
    }
}
