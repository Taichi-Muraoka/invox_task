"use strict";

import PageComponentBase from "./page-component-base";
import PageModal from "../page-components/page-modal";
import PageEvent from "../page-components/page-event";

/**
 * ページコンポーネント: 入力フォーム
 */
export default class PageInputForm extends PageComponentBase {
    /*
     * Vueインスタンスを取得
     */
    getVueApp(option = {}) {
        //--------------------
        // オプションの定義
        //--------------------

        // 確認ダイアログの文言(新規登録)
        if (option["confirmStrNew"] == undefined) {
            // option["confirmStrNew"] = "送信";
            option["confirmStrNew"] = "";
        }
    
        // 編集完了後の処理(登録・変更・削除後)
        if (option["afterEdit"] == undefined) {
            option["afterEdit"] = () => {};
        }

        // Vueにdataを追加
        if (option["vueData"] == undefined) {
            option["vueData"] = {};
        }

        // Vueにmethodsを追加
        if (option["vueMethods"] == undefined) {
            option["vueMethods"] = {};
        }

        // Vueのmountedイベントを呼ぶ
        // プルダウンのチェンジイベントを発生させる想定
        if (option["vueMounted"] == undefined) {
            option["vueMounted"] = ($vue, option) => {};
        }

        // URLの接尾語(画面ごとにURLを変えたい場合)
        if (ValueCom.isEmpty(option["urlSuffix"])) {
            option["urlSuffix"] = "";
        } else {
            option["urlSuffix"] = "_" + option["urlSuffix"];
        }

        // モーダル確認ダイアログを使用するかどうか(使用する場合、モーダルIDを指定)
        if (ValueCom.isEmpty(option["confirmModal"])) {
            option["confirmModal"] = "";
        }

        // 送信時、処理中ダイアログを強制的に表示するかどうか
        if (option["progressShow"] == undefined) {
            option["progressShow"] = false;
        }

        // ターゲットのID
        if (option["id"] == undefined) {
            option["id"] = "#app-form";
        }

        //--------------------
        // Vueの定義
        //--------------------

        // フォームのID
        const formId = option["id"];

        // 編集時にformのvalueから値を取得するためformの定義を作成する。
        const formData = FormCom.getFormArrayData(formId);

        // Vue:フォーム
        const self = this;
        const vueApp = {
            // オプションでdataを追加する
            data() {
                return Object.assign(
                    {
                        // VueのIdを格納する
                        appId: formId,
                        // フォームインプット
                        //form: {},
                        form: formData,
                        // フォームエラー
                        form_err: {
                            msg: {},
                            class: {},
                        },

                        // プルダウン選択後の詳細を格納する
                        selectGetItem: {},

                        // 確認モーダル
                        vueModal: null,

                        // オプションを保持しておく
                        option: null,

                        // バリデーション後かどうか
                        afterValidate: false,
                        // エラーのキーを格納
                        validateErrKey: [],
                    },
                    option["vueData"]
                );
            },
            mounted() {
                // optionを保持
                this.option = option;

                // ライブラリの初期化
                self.initLibs(this, option);

                // 確認モーダルの表示用
                if (!ValueCom.isEmpty(option["confirmModal"])) {
                    const pageModal = new PageModal();
                    this.vueModal = pageModal.getVueApp({
                        useShowEvent: false,
                        id: option["confirmModal"],
                    });
                }

                // 呼び出し元のmouted処理を呼ぶ
                option["vueMounted"](this, option);
            },
            updated() {
                // バリデーションエラー時に、エラー箇所にスクロールする
                // これはVueの更新後じゃないと、エラーメッセージが表示されず、スクロール位置が取れないため
                if (this.afterValidate) {
                    // 一番上のエラーを探す
                    var errSpan = null;
                    var errSpans = $.find(".form-validation");
                    for (var i = 0; i < errSpans.length; i++) {
                        var dataId = $(errSpans[i]).attr("data-id");
                        if (this.validateErrKey.indexOf(dataId) >= 0) {
                            errSpan = errSpans[i];
                            break;
                        }
                    }

                    // スクロール
                    if (errSpan) {
                        // エラー箇所へ移動する
                        var position = $(errSpan).offset().top;
                        var speed = 300;
                        $("html, body").animate(
                            { scrollTop: position },
                            speed,
                            "swing"
                        );
                    }

                    // リセット
                    this.afterValidate = false;
                    this.validateErrKey = [];
                }

                // Vue更新後、ライブラリの初期化
                self.updatedLibs(this);
            },
            // オプションでメソッドを追加する
            methods: Object.assign(
                {
                    // 送信ボタン
                    submitNew: function () {
                        // 新規登録処理
                        if (option["afterNew"]) {
                            option["afterEdit"] = option["afterNew"];
                        }
                        self._sendNew(this, option);
                    },
                    // ファイルアップロード(アップロード済みファイル削除。実際はhidden値を削除する)
                    fileUploadedDelete: function (event) {
                        var uploaded = $(event.target).parent().parent();
                        // hiddenを取得
                        var updHidden = uploaded
                            .find("input[type='hidden']")
                            .get(0);
                        // Vueのformから削除
                        this.form[updHidden.id] = "";
                        // divごと非表示にする
                        uploaded.remove();
                    },
                },
                option["vueMethods"]
            ),
        };

        return this.createComponent(vueApp, formId);
    }

    /**
     * 送信用のフォームデータを取得
     *
     * @param vueインスタンス
     */
    _getSendFormData($vue) {
        // フォームの値を取得(アップロードファイルも取得)
        const formData = new FormData();

        // 通常の入力用
        for (const [key, inputElement] of Object.entries($vue.form)) {
            // フォームを追加
            formData.append(key, inputElement);
        }

        // ファイルのアップロード用
        var existFile = false;
        const files = $("input[type='file']").filter(function (idx, el) {
            return el.id.startsWith("file_");
        });

        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                // ファイルを取得
                const fileElement = files[i];
                const key = fileElement.id;

                // ファイルを取得(1つのinput fileで複数選択しないので0しか無いはず)
                for (var j = 0; j < fileElement.files.length; j++) {
                    // フラグ(アップロードするファイルがある場合)
                    existFile = true;

                    // ファイルを取得
                    const file = fileElement.files[j];

                    // MEMO: 複数ファイル選択に対応する場合は、j番目を追加しないといけないはず
                    formData.append(key, file);
                }
            }
        }

        // アップロード用のコンテンツタイプ
        var formHeader = {};
        if (existFile) {
            // これがないとコントローラへファイルがアップロードされない
            formHeader = {
                headers: { "Content-Type": "multipart/form-data" },
            };
        }

        return {
            data: formData,
            header: formHeader,
            // アップロードが含まれるかどうか
            upload: existFile,
        };
    }

    /**
     * 新規登録
     *
     * @param vueインスタンス
     */
    _sendNew($vue, option) {
        // 送信フォームのデータを取得する
        var sendData = this._getSendFormData($vue);

        // 画像パス
        var image_path = null;

        const self = this;
        AjaxCom.getPromise()
            .then(() => {
                // バリデート(例：http://localhost:8000/sample/new と同じ階層を想定)
                var url =
                    UrlCom.getFuncUrl() + "/vd_input" + option["urlSuffix"];

                // ファイルアップロード時は大きいファイルが想定されるのでローディングを表示
                // 空き時間登録のチェックボックスが多くやや時間がかかるので強制的に表示する場合も想定
                if (sendData.upload || option["progressShow"]) {
                    // ダイアログ
                    appDialogCom.progressShow();
                }
                // バリデーション
                return axios.post(url, sendData.data, sendData.header);
            })
            .then((response) => {
                // ファイルアップロード時は大きいファイルが想定されるのでローディングを表示
                if (sendData.upload || option["progressShow"]) {
                    // ダイアログ
                    appDialogCom.progressHide();
                }
                // バリデーション結果をチェック
                if (!self.validateCheck($vue, response)) {
                    // 処理を抜ける
                    return AjaxCom.exit();
                }

                // 確認ダイアログ
                if (
                    Object.keys(response.data).length == 1 &&
                    response.data["confirm_modal_data"]
                ) {
                    // 確認モーダルダイアログ

                    // モーダルに表示したいデータをセットする
                    $vue.vueModal.item = response.data["confirm_modal_data"];

                    // モーダルを表示する
                    $vue.vueModal.show();

                    // 確認
                    return $vue.vueModal.confirm();
                } else {
                    // 通常の確認ダイアログ
                    return appDialogCom.confirmSend(option["confirmStrNew"]);
                }
            })
            .then((flg) => {
                if (!flg) {
                    // いいえを押した場合
                    return AjaxCom.exit();
                }

                // ダイアログ
                appDialogCom.progressShow();

                // 新規登録
                var url = UrlCom.getFuncUrl() + "/create" + option["urlSuffix"];

                
                // 送信
                return axios.post(url, sendData.data, sendData.header);
            })
            .then((response) => {
                // ダイアログ
                appDialogCom.progressHide();

                // エラー応答の場合は、アラートを表示する
                if (
                    Object.keys(response.data).length == 1 &&
                    response.data["error"]
                ) {
                    appDialogCom.alert(response.data["error"]);
                    return AjaxCom.exit();
                }

                // 通常完了メッセージ
                return appDialogCom.success(option["confirmStrNew"]);
            })
            .catch(AjaxCom.fail);
    }
}
