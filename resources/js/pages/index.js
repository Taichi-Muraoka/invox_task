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
        // 送信完了後はページをリロードする
        var afterEdit = () => {
            window.location.reload();
        };

        // Vue: 入力フォーム
        this.getVueInputForm({
            afterEdit: afterEdit,
            // response: true,
        });
    }
}