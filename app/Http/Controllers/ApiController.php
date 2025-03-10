<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
// 仮API
use App\Http\Controllers\Traits\AiAnalysisTrait;

class ApiController extends Controller
{
    /**
     * 擬似API
     *
     * @param \Illuminate\Http\Request $request リクエスト
     * @return void
     */
    public function index()
    {
        // データ確認用
        $image_data = ImageData::all();
        return response()->json([
            'image_data' => $image_data,
        ], 200);
    }

    public function store(Request $request)
    {
        // リクエストで送られてきた画像のパスは仮APIのため使用しない

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
