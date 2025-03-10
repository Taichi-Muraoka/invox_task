<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Form - コントローラ共通処理
 */
trait CtrlFormTrait
{
    /**
     * 検索セッションの接頭辞を取得
     */
    private function getSessionPrefix()
    {

        return 'search_cond/';
    }


    //------------------------------
    // バリデーション
    //------------------------------

    /**
     * リクエストのIDの数値・必須チェック
     * getData, edit, delete, execModalなど
     *
     * @param  mixed  $ids idが格納されている変数(複数指定可能)
     */
    protected function validateIds(...$ids)
    {
        // LaravelのValidationを使う
        $rules = array();

        // IDを追加。key名は任意
        $arrCheck = [];
        $keyNum = 0;
        foreach ($ids as $id) {

            // キーを作成
            $keyNum++;
            $key = 'key-' . $keyNum;

            // 数値、必須チェック
            $rules += [$key => ['required', 'integer']];
            // チェック対象を配列に格納
            $arrCheck[$key] = $id;
        }

        // バリデーション
        $validator = Validator::make($arrCheck, $rules);
        if ($validator->fails()) {
            // エラー時。エラー時は不正な値としてエラーレスポンスを返却
            $this->illegalResponseErr();
        }
    }

    /**
     * リクエストの中のIDの数値・必須チェック
     * getData, edit, delete, execModalなど
     *
     * @param  mixed  $request チェック対象のリクエスト
     * @param  mixed  $ids idのキー名(複数指定可能)
     */
    protected function validateIdsFromRequest(Request $request, ...$ids)
    {
        $arrCheck = [];
        foreach ($ids as $id) {
            // チェック対象を配列に格納
            $arrCheck[] = $request[$id];
        }

        // 可変引数で呼び出す
        $this->validateIds(...$arrCheck);
    }

    /**
     * リクエストの日付・必須チェック
     * getData, edit, delete, execModalなど
     *
     * @param  mixed  $dates dateが格納されている変数(複数指定可能)
     */
    protected function validateDates(...$dates)
    {
        // LaravelのValidationを使う
        $rules = array();

        // IDを追加。key名は任意
        $arrCheck = [];
        $keyNum = 0;
        foreach ($dates as $date) {

            // キーを作成
            $keyNum++;
            $key = 'key-' . $keyNum;

            // 日付、必須チェック
            $rules += [$key => ['required', 'date_format:Y-m-d']];
            // チェック対象を配列に格納
            $arrCheck[$key] = $date;
        }

        // バリデーション
        $validator = Validator::make($arrCheck, $rules);
        if ($validator->fails()) {
            // エラー時。エラー時は不正な値としてエラーレスポンスを返却
            $this->illegalResponseErr();
        }
    }

    /**
     * リクエストの中の日付・必須チェック
     * getData, edit, delete, execModalなど
     *
     * @param  mixed  $request チェック対象のリクエスト
     * @param  mixed  $dates dateのキー名(複数指定可能)
     */
    protected function validateDatesFromRequest(Request $request, ...$dates)
    {
        $arrCheck = [];
        foreach ($dates as $date) {
            // チェック対象を配列に格納
            $arrCheck[] = $request[$date];
        }

        // 可変引数で呼び出す
        $this->validateDates(...$arrCheck);
    }

    /**
     * ルートパラメータのチェック
     *
     * @param  mixed  $params チェック対象のパラメータ
     * @param  mixed  $rules バリデーションルール
     */
    protected function validateFromParam($params, $rules)
    {
        // パラメータのバリデーション
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            // エラー時。エラー時は不正な値としてエラーレスポンスを返却
            $this->illegalResponseErr();
        }
    }

    //------------------------------
    // 変換
    //------------------------------

    /**
     * Ym形式の値を、Y-m-dに変換する
     *
     * @param int $ym Ym形式の日付
     */
    protected function fmYmToDate($ym)
    {

        if (strlen($ym) == 6) {
            // Ym→Y-m-d
            $date = substr($ym, 0, 4) . '-' . substr($ym, -2) . '-01';
        } else {
            // エラー
            $this->illegalResponseErr();
        }

        // 日付のバリデーション
        $this->validateDates($date);

        return $date;
    }

    /**
     * オブジェクトを配列に変換
     * プルダウンのリストを非同期で返却する際に使用する。
     * (オブジェクトのまま返却すると並び順が変わってしまうため)
     *
     * @param object $obj
     * @return array 配列
     */
    function objToArray($obj)
    {
        $rtn = [];
        foreach ($obj as $value) {
            $rtn[] = $value;
        }
        return $rtn;
    }
}
