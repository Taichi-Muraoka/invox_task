<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
// 仮API
use App\Http\Controllers\Traits\AiAnalysisTrait;

class IndexController extends Controller
{

    // 仮API
    use AiAnalysisTrait;

    /**
     * リダイレクト
     * ログイン画面がないためindexにリダイレクトする
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function redirectIndex()
    {
        return redirect('index');
    }

    /**
     * index画面
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * バリデーション(登録用)
     *
     * @param \Illuminate\Http\Request $request リクエスト
     * @return mixed バリデーション結果
     */
    public function validationForInput(Request $request)
    {
        // リクエストデータチェック
        $validator = Validator::make($request->all(), $this->rulesForInput($request));
        return $validator->errors();
    }

    /**
     * バリデーションルールを取得(登録用)
     *
     * @param \Illuminate\Http\Request $request リクエスト
     * @return array ルール
     */
    private function rulesForInput(?Request $request)
    {
        $rules = array();

        $rules += ImageData::fieldRules('image_path');

        // ファイルのタイプのチェック(「file_項目名」の用にチェックする)
        $rules += ['file_image' => [
            // 必須
            'required',
            // ファイル
            'file',
            // jpg, png , gifのみ
            'mimes:jpg,png,gif',
        ]];

        return $rules;
    }

    /**
     * 登録処理
     *
     * @param \Illuminate\Http\Request $request リクエスト
     * @return void
     */
    public function create(Request $request)
    {
        // アップロードされた画像ファイルのパスを取得
        $image_path = $request['file_image'];

        // リクエストのタイムスタンプを作成
        $request_timestamp = date('Y-m-d H:i:s');

        // 画像分析のAPIにパスを渡す
        // 今回はTraitsの関数がAPIだと仮定する
        $response_json = $this->analysis($image_path);

        // レスポンスのタイムスタンプを作成
        $response_timestamp = date('Y-m-d H:i:s');

        // jsonで送られてきたレスポンスを配列に変換
        $response = json_decode($response_json, true);

        try {
            // トランザクション(例外時は自動的にロールバック)
            DB::transaction(function () use ($image_path, $response, $request_timestamp, $response_timestamp) {
                // インスタンスの生成
                $image_data = new ImageData;
                $image_data->image_path = $image_path;
                $image_data->success = $response['success'];
                $image_data->message = $response['message'];
                $image_data->class = $response['estimated_data']['class'];
                $image_data->confidence = $response['estimated_data']['confidence'];
                $image_data->request_timestamp = $request_timestamp;
                $image_data->response_timestamp = $response_timestamp;

                // DBに保存
                $image_data->save();
            });
        } catch (\Exception  $e) {
            // この時点では補足できないエラーとして、詳細は返さずエラーとする
            Log::error($e);
            return $this->illegalResponseErr();
        }

        return;
    }
}
