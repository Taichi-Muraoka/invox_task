<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 画像データ - モデル
 */
class ImageData extends Model
{

    // モデルの共通処理
    use \App\Traits\ModelTrait;

    // 論理削除
    use SoftDeletes;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'image_datas';

    /**
     * テーブルの主キー
     *
     * @var array
     */

    protected $primaryKey = 'id';

    /**
     * IDが自動増分されるか
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'image_path',
        'success',
        'message',
        'class',
        'confidence'
    ];

    /**
     * 属性のキャスト
     *
     * @var array
     */
    protected $casts = [
        'image_path' => 'string',
    ];

    /**
     * 属性に対するモデルのデフォルト値
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * 配列に含めない属性
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * モデルの「初期起動」メソッド
     *
     * @return void
     */
    protected static function booted()
    {
        // 更新時、空白をnullに変換する処理
        self::whenSaveEmptyToNull();

        // テーブル操作時、ログを残す処理
        self::saveToLog();
    }

    //-------------------------------
    // 項目定義
    //-------------------------------

    /**
     * テーブル項目の定義
     *
     * @return array
     */
    protected static function getFieldRules()
    {
        static $_fieldRules = [
            'id' => ['integer'],
            'image_path' => ['string', 'max:255'],
            'success' => ['integer', 'max:1', 'digits:1'],
            'class' => ['integer', 'max:99999999999'],
            'message' => ['string', 'max:255'],
            'confidence' => ['integer', 'max:99999999999'],
        ];
        return $_fieldRules;
    }
}
