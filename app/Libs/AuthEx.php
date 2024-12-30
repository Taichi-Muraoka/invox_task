<?php

namespace App\Libs;

use App\Consts\AppConst;
use App\Models\RoleTypeMenu;
use Illuminate\Support\Facades\Gate;

/**
 * 認証に関する共通クラス
 */
class AuthEx
{
    /**
     * 権限の追加
     */
    public static function addGateDefine()
    {

        //--------------------
        // 権限の追加
        //--------------------

        // メニューへのアクセス権限のチェック
        // Menu 1 Irradiation Schedule
        Gate::define('isMenu1', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_1));
        });

        // Menu 2 Customer Sample
        Gate::define('isMenu2', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_2));
        });
        Gate::define('isMenu2_reg', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_2, true, false, false));
        });
        Gate::define('isMenu2_chg', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_2, false, true, false));
        });
        Gate::define('isMenu2_ref', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_2, true, true, true));
        });
        Gate::define('isMenu2_dtl', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_2, true, true, true));
        });

        // Menu 3 Irradiation Booking
        Gate::define('isMenu3', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_3));
        });
        Gate::define('isMenu3_dose_time_view', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_3, true, true, true));
        });
        Gate::define('isMenu3_dose', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_3, true, true, true));
        });
        Gate::define('isMenu3_time', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_3, true, true, true));
        });
        Gate::define('isMenu3_view', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_3, true, true, true));
        });
        Gate::define('isMenu3_dtl', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_3, true, true, true));
        });

        // Menu 4 Irradiation Result
        Gate::define('isMenu4', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_4));
        });
        Gate::define('isMenu4_reg', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_4, true, false, false));
        });
        Gate::define('isMenu4_chg', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_4, false, true, false));
        });
        Gate::define('isMenu4_ref', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_4, true, true, true));
        });

        // Menu 5 Irradiation Customer Report
        Gate::define('isMenu5', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_5));
        });
        Gate::define('isMenu5_issue', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_5, true, false, false));
        });
        Gate::define('isMenu5_chg', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_5, false, true, false));
        });
        Gate::define('isMenu5_ref', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_5, true, true, true));
        });
        Gate::define('isMenu5_dl', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_5, true, true, true));
        });

        // Menu 6 Irradiation Result Analysis
        Gate::define('isMenu6', function ($user) {
            return (self::isRoleMenuAuthority($user, AppConst::CODE_MASTER_1_6, true, true, true));
        });

        // Menu 7 Customer Sample Data Management
        Gate::define('isMenu7', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_7));
        });
        // Menu 8 Irradiation Data Management
        Gate::define('isMenu8', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_8));
        });
        // Menu 9 User Data Management
        Gate::define('isMenu9', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_9));
        });
        // Menu 10 Basic Data Management
        Gate::define('isMenu10', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_10));
        });
        // Menu 11 Safety Inspection Management
        Gate::define('isMenu11', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_11));
        });
        // Menu 12 System Development Credit
        Gate::define('isMenu12', function ($user) {
            return (self::isRoleMenu($user->role_type_id, AppConst::CODE_MASTER_1_12));
        });

        //--------------------
        // 権限グループ
        //--------------------

        // マイページ共通
        Gate::define('mypage-common', function ($user) {
            return ($user->role_type_id == 1 || $user->role_type_id == 2 || $user->role_type_id == 3);
        });
    }

    /**
     * ログインユーザが対象のメニューの権限があるか
     */
    private static function isRoleMenu($roleType, $checkMenu)
    {
        $menu = RoleTypeMenu::where('use_menu_code', $checkMenu)
            ->where('role_type_id', $roleType)
            ->first();
        return isset($menu);
    }

    /**
     * ログインユーザが対象のメニューの権限と有効な操作権限があるか
     */
    private static function isRoleMenuAuthority($user, $checkMenu, $isReg, $isChg, $isRef)
    {
        // メニューの権限があるか
        $menu = RoleTypeMenu::where('use_menu_code', $checkMenu)
            ->where('role_type_id', $user->role_type_id)
            ->first();

        if (isset($menu)) {
            // 操作権限があるか
            if ($isReg == true && $user->is_registration == AppConst::BOOL_TRUE) {
                return true;
            }
            if ($isChg == true && $user->is_changedel == AppConst::BOOL_TRUE) {
                return true;
            }
            if ($isRef == true && $user->is_refer == AppConst::BOOL_TRUE) {
                return true;
            }
        }

        return false;
    }
}
