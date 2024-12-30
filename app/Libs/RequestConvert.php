<?php

namespace App\Libs;

use Illuminate\Support\Str;

/**
 * Requestの共通処理
 */
class RequestConvert
{

    /**
     * 本番環境の場合、httpをhttpsに変換する
     */
    public function getRootUrl($url)
    {
        if (config('app.env') === 'production') {
            if(Str::startsWith(config('app.url'), 'https')){
                return str_replace('http://', 'https://', $url);
            }else{
                return $url;
            }
        } else {
            return $url;
        }
    }
}
