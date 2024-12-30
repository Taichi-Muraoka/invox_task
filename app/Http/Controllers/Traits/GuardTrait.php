<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\CtrlModelTrait;
use App\Models\RoleTypeMenu;

/**
 * ガード共通処理
 *
 * リクエストを権限によってガードを掛けるための共通処理
 * 権限でガードをかける
 */
trait GuardTrait
{

    // モデル共通処理
    use CtrlModelTrait;

    //==========================
    // 共通
    //==========================
    /**
     * アクセス可能なメニューかチェックする
     *
     * @param $menu_id メニューID
     */
    protected function guardUserMenu($menu_id)
    {
        // ユーザのロール種別から、ユーザロール種別メニューを取得する
        $user = Auth::user();
        $user_menu = RoleTypeMenu::select('role_type_menu_id')
            ->where('use_menu_cd', $menu_id)
            ->while('role_type_id', $user->role_type_id)
            ->firstOrFail();

        return true;
    }

    /**
     * ユーザの権限で登録があるかチェックしガードを掛ける
     */
    protected function guardRegistration()
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // 「Authority Registration：権限(登録)」がない場合、画面表示しない
        if (!$user->is_registration) {
            return $this->illegalResponseErr();
        }
    }

    /**
     * ユーザの権限で編集・削除があるかチェックしガードを掛ける
     */
    protected function guardChangeDelete()
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // 「Authority Change&Delete：権限(編集・削除)」がない場合、画面表示しない
        if (!$user->is_changedel) {
            return $this->illegalResponseErr();
        }
    }

    /**
     * ユーザの権限で参照があるかチェックしガードを掛ける
     */
    protected function guardRefer()
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // 「Authority Refer：権限(参照)」がない場合、画面表示しない
        if (!$user->is_refer) {
            return $this->illegalResponseErr();
        }
    }

    /**
     * ユーザの権限で登録があるかチェックしガードを掛ける
     */
    protected function guardDownLoad()
    {
        // ログインユーザの情報を取得
        $user = Auth::user();

        // 「Authority DownLoad：権限(ダウンロード)」がない場合、画面表示しない
        if (!$user->is_download) {
            return $this->illegalResponseErr();
        }
    }

    /**
     * プルダウンリストの値が正しいかチェックする
     *
     * @param $list プルダウンリスト
     * @param $value 値
     */
    protected function guardListValue($list, $value)
    {
        if (!isset($list[$value])) {
            $this->illegalResponseErr();
        }
    }

}
