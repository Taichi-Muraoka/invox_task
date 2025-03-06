"use strict";

/*
 * index
 */
export default class AppClass extends PageBase {
    /**
     * コンストラクタ
     */
    constructor() {
        super();
    }

    /**
     * 開始処理
     */
    start() {
        $('input').on('change', function () {
            //propを使って、file[0]にアクセスする
            var file = $(this).prop('files')[0];
            //text()で要素内のテキストを変更する
            $('.custom-file-label').text(file.name);
            console.log(file);
        });

        // Vue: 入力フォーム
        this.getVueInputForm({});
    }
}