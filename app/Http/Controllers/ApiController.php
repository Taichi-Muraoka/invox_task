<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageData;

class ApiController extends Controller
{
    /**
     * 仮API 登録データ確認用
     * GETリクエストで確認
     *
     * @param null
     * @return json
     */
    public function index()
    {
        // データ確認用
        $image_data = ImageData::all();
        return response()->json([
            'image_data' => $image_data,
        ], 200);
    }

    /**
     * 仮API 
     * 仮のため固定のレスポンス内容を返す
     *
     * @param Reuest
     * @return json
     */
    public function store(Request $request)
    {
        // リクエストで送られてきた画像のパスは仮APIのため使用しない

        // レスポンス内容格納用
        $response = [];

        // 仮APIのため、レスポンス内容は固定
        // 実際は送られてきた画像パスを元に
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
