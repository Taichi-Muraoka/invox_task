"use strict";

/*
 * フォーム処理クラス
 */
export default class FormCom {
    /**
     * 指定されたIDのForm値を配列で取得
     * Vueのdataプロパティにセットするために使用
     */
    static getFormArrayData(formId) {
        var inputVals = {};
        $(formId)
            .find("input,select,textarea")
            .each(function (index, element) {
                // ID
                var id = $(element).attr("id");
                // name
                var name = $(element).attr("name");

                //if (!ValueCom.isEmpty(id) && !$(element).is(":disabled")) {
                // disabledのform要素は全て設定対象外としていたが、checkboxのみdisabledも対象とする
                if (!ValueCom.isEmpty(id)) {
                    if ($(element).is(":checkbox")) {
                        // チェックボックスの場合(同じnameが複数ある想定)
                        // arrayで渡す
                        if (!inputVals[name]) {
                            // nameが同じ場合、配列で扱う
                            inputVals[name] = [];
                        }

                        if ($(element).is(":checked")) {
                            // チェックされた値を取得
                            inputVals[name].push($(element).val());
                        }
                    //} else if ($(element).is(":radio")) {
                    } else if ($(element).is(":radio") && !$(element).is(":disabled")) {
                        // ラジオの場合。選択されているものを取得

                        if ($(element).is(":checked")) {
                            inputVals[name] = $(element).val();
                        }
                    //} else if ($(element).is(":file")) {
                    } else if ($(element).is(":file") && !$(element).is(":disabled")) {
                        // file選択のinputは無視する。
                    //} else {
                    } else if (!$(element).is(":disabled")){
                        inputVals[id] = $(element).val();
                    }
                }
                //if (ValueCom.isEmpty(id) && !ValueCom.isEmpty(name) && !$(element).is(":disabled")) {
                if (ValueCom.isEmpty(id) && !ValueCom.isEmpty(name)) {
                    // チェックボックスでid名が動的である場合、
                    // 初期化時にidが取得できないためこちらで対応する
                    if ($(element).is(":checkbox")) {
                        // チェックボックスの場合(同じnameが複数ある想定)
                        // arrayで渡す
                        if (!inputVals[name]) {
                            // nameが同じ場合、配列で扱う
                            inputVals[name] = [];
                        }

                        if ($(element).is(":checked")) {
                            // チェックされた値を取得
                            inputVals[name].push($(element).val());
                        }
                    }
                }
            });
        return inputVals;
    }

    /**
     * カード用読み込み中 開始
     *
     * @param id
     */
    static loadingForCardOn(id) {
        $(id).append(
            '<div class="overlay"><i class="fas fa-4x fa-circle-notch fast-spin"></i></div>'
        );
    }

    /**
     * カード用読み込み中 終了
     *
     * @param id
     */
    static loadingForCardOff(id) {
        // アニメーション
        $(id + " div.overlay")
            .fadeOut("fast")
            .queue(function () {
                this.remove();
            });
    }
}
