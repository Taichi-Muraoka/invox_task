<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\DB;

trait AiAnalysisTrait
{
    /**
     * 画像を解析してレスポンスを返す擬似API
     */
    protected function analysis($image_path)
    {
        // レスポンス内容格納用
        $response = [];

        // 擬似APIのため、レスポンス内容は固定
        try {
            // 成功
            $response = [
                "success" => true,
                "message" => "success",
                "estimated_data" => [
                    "class" => 3,
                    "confidence" => 0.8683
                ],
            ];
        } catch (\Exception $e) {
            // エラー
            $response = [
                "success" => false,
                "message" => "Error:E50012",
                "estimated_data" => [],
            ];
        }

        return json_encode($response);
    }
}