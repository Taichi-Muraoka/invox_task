<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_datas', function (Blueprint $table) {
            /* カラム */
            $table->increments('id')->comment('ID');
            $table->string('image_path', 255)->nullable()->comment('画像パス');
            $table->unsignedSmallInteger('success')->comment('成功フラグ');
            $table->string('message', 255)->nullable()->comment('メッセージ');
            $table->unsignedInteger('class')->nullable()->comment('クラス');
            $table->decimal('confidence', 5, 4)->nullable()->comment('Confidence');
            $table->timestamp('request_timestamp')->nullable()->comment('リクエストタイムスタンプ');
            $table->timestamp('response_timestamp')->nullable()->comment('レスポンスタイムスタンプ');
            $table->timestamps();
            $table->softDeletes();

            /* インデックス */
            $table->primary('id');

            /* テーブル名コメント */
            $table->comment('画像解析データ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_datas');
    }
};
