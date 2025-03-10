"use strict";

import PageModal from "../page-components/page-modal";
import PageInputForm from "../page-components/page-input-form";

/**
 * ページのスーパークラス
 */
export default class PageBase {
    /**
     * コンストラクタ
     */
    constructor() {
        // 抽象メソッドの確認。定義を必須とした
        if (this.start === undefined) {
            throw new TypeError("Must override start method.");
        }
    }

    //--------------------------------------------
    // 共通処理
    //--------------------------------------------

    

    //--------------------------------------------
    // モーダル処理
    //--------------------------------------------

    /*
     * モーダルのVue
     */
    getVueModal(option = {}) {
        const pageModal = new PageModal();
        return pageModal.getVueApp(option);
    }

    //--------------------------------------------
    // 一覧処理
    //--------------------------------------------


    //--------------------------------------------
    // 送信処理
    //--------------------------------------------

    /*
     * 入力フォームのVue
     */
    getVueInputForm(option = {}) {
        const pageInputForm = new PageInputForm();
        return pageInputForm.getVueApp(option);
    }
}
