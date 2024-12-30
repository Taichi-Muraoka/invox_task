<?php

namespace App\Filters;

use App\Consts\AppConst;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use App\Libs\AuthEx;
use App\Http\Controllers\Traits\CtrlModelTrait;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * AdminLteの左メニューのカスタマイズ
 */
class AppMenuFilter implements FilterInterface
{
    // モデル共通処理
    use CtrlModelTrait;

    public function transform($item)
    {
        // アカウント情報取得
        $account = Auth::user();

        return $item;
    }
}
