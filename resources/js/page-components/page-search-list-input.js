"use strict";

import PageComponentBase from "./page-component-base";
import PageModal from "../page-components/page-modal";

/**
 * ページコンポーネント: 検索結果一覧 + input
 */
export default class PageSearchListInput extends PageComponentBase {
    /*
     * Vueインスタンスを取得
     */
    getVueApp(option = {}) {
        //--------------------
        // オプションの定義
        //--------------------

        // モックかどうか。通信処理は行われない
        if (option["isMock"] == undefined) {
            option["isMock"] = false;
        }

        // ターゲットのID
        if (option["id"] == undefined) {
            option["id"] = "#app-serch-list";
        }

        // URLの接尾語(画面ごとにURLを変えたい場合)
        if (ValueCom.isEmpty(option["urlSuffix"])) {
            option["urlSuffix"] = "";
        } else if (option["urlSuffix"].charAt(0) == "_") {
            // 何もしない(vueのoptionを渡された場合)
        } else {
            option["urlSuffix"] = "_" + option["urlSuffix"];
        }

        // 検索完了後の処理(検索ボタンの活性のため)
        if (option["afterSearch"] == undefined) {
            option["afterSearch"] = () => {};
        }

        // 検索完了後の処理(検索ボタンの活性のため)
        if (option["afterSearch2"] == undefined) {
            option["afterSearch2"] = () => {};
        }

        // Vueにdataを追加
        if (option["vueData"] == undefined) {
            option["vueData"] = {};
        }

        // Vueにmethodsを追加
        if (option["vueMethods"] == undefined) {
            option["vueMethods"] = {};
        }

        //--------------------
        // Vueの定義
        //--------------------

        // divのid
        const id = option["id"];

        // hidden値を取得するためにFormの値を取得
        const formData = FormCom.getFormArrayData(id);

        //--------------------
        // Vueの定義
        //--------------------

        const self = this;
        const vueApp = {
            data() {
                return Object.assign(
                    {
                        paginator: {},
                        elements: [],

                        // 検索フォームの条件(保持用)
                        searchForm: {},

                        // Hidden値などのformを保持。子画面で一覧がある場合など(請求情報一覧)
                        form: formData,
                        // フォームエラー
                        form_err: {
                            msg: {},
                            class: {},
                        },
                        // 確認モーダル
                        vueModal: null,

                        // オプションを保持しておく
                        option: null,

                        // バリデーション後かどうか
                        afterValidate: false,
                        // エラーのキーを格納
                        validateErrKey: [],

                        // ページ数を保持
                        page: 1,
                    },
                    option["vueData"]
                );
            },
            mounted() {
                // optionを保持
                this.option = option;

                // // ライブラリの初期化
                // self.initLibs(this, option);

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
            methods: Object.assign(
                {
                    //--------------------------
                    // 検索一覧クリア
                    //--------------------------
                    clear: function () {
                        // クリア
                        this.paginator = {};
                        this.elements = {};

                        // チェックボックスクリア
                        this.form["checkBoxes"] = [];
                    },
                    //--------------------------
                    // 検索
                    //--------------------------
                    search: function (
                        $searchForm = {},
                        page = 1,
                        scroll = false
                    ) {
                        if (scroll) {
                            // モーダルかどうか判断
                            const parent =
                                $("#search-top").parent(".modal-body");
                            const speed = 300;

                            // 一覧のトップへ移動する
                            if (parent.length == 0) {
                                // モーダル以外
                                const position = $("#search-top").offset().top;
                                $("html, body").animate(
                                    { scrollTop: position },
                                    speed,
                                    "swing"
                                );
                            } else {
                                // モーダル内の相対位置を取得
                                const pos =
                                    document.getElementById(
                                        "search-top"
                                    ).offsetTop;
                                $(
                                    ".modal-dialog-scrollable .modal-body"
                                ).animate({ scrollTop: pos }, speed, "swing");
                            }
                        }

                        // ページ数を保持する
                        this.page = page;

                        AjaxCom.getPromise()
                            .then(() => {
                                // ローディング開始
                                FormCom.loadingForCardOn(id);

                                // フォームデータに加えてページも追加
                                const sendData = Object.assign(
                                    $searchForm,
                                    {
                                        page: page,
                                    },
                                    // hiddenを送信
                                    this.form
                                );

                                // 検索 (例：http://localhost:8000/sample と同じ階層を想定)
                                const url =
                                    UrlCom.getFuncUrl() +
                                    "/search" +
                                    option["urlSuffix"];

                                // モック時は送信しない
                                if (!option["isMock"]) {
                                    return axios.post(url, sendData);
                                } else {
                                    // ダミーウェイト
                                    return DummyCom.wait();
                                }
                            })
                            .then((response) => {
                                //console.log(response);

                                // モック時は処理しない
                                if (!option["isMock"]) {
                                    // ページャデータ
                                    const paginator = response.data.paginator;
                                    const elements = response.data.elements;

                                    // ページャをセット
                                    this.paginator = paginator;

                                    // 件数表示
                                    this.paginator.total =
                                        "The Number Of Data : " +
                                        paginator.total;

                                    // ページャーの表示
                                    this.elements = elements;

                                    // チェックボックスクリア
                                    this.form["checkBoxes"] = [];

                                    // 検索した条件を保持しておく(ページャなど)
                                    //this.searchForm = $searchForm;
                                    // 上記のようにVueのプロパティをそのままセットすると、
                                    // リアルタイムで反映されてしまうので、以下のようにセットし直す
                                    this.searchForm = {};
                                    for (const [key, value] of Object.entries(
                                        $searchForm
                                    )) {
                                        this.searchForm[key] = value;
                                    }
                                }

                                // ローディング終了
                                FormCom.loadingForCardOff(id);

                                // 検索後の処理
                                option["afterSearch"](this);
                            })
                            .catch(AjaxCom.fail);
                    },

                    //--------------------------
                    // ページャのリンクをクリック
                    //--------------------------
                    page_link: function (page) {
                        // 検索
                        this.search(this.searchForm, page, true);
                    },

                    //--------------------------
                    // 再描画
                    //--------------------------
                    // 検索条件・ページ数はそのままで再検索する
                    // 受付ボタンなどをクリックし、再度一覧を描画し直す際、
                    // 1ページ目に戻らず、そのままのページでかつ検索条件も同じとする
                    refresh: function () {
                        // 検索
                        this.search(this.searchForm, this.page, false);
                    },
                    //--------------------------
                    // 削除
                    //--------------------------
                    // バリデーションあり削除ボタン
                    submitValidationDelete: function () {
                        // 削除処理
                        self._sendValidationDelete(this, option);
                    },
                    //--------------------------
                    // 登録
                    //--------------------------
                    //
                    submitNew: function () {
                        // 登録処理
                        self._sendNew(this, option);
                    },
                },
                option["vueMethods"]
            ),
        };

        return this.createComponent(vueApp, id);
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
     * 削除(バリデーションあり)
     *
     * @param vueインスタンス
     */
    _sendValidationDelete($vue, option) {
        // 送信フォームのデータを取得する
        var sendData = this._getSendFormData($vue);

        const self = this;
        AjaxCom.getPromise()
            .then((response) => {
                // バリデート(例：http://localhost:8000/sample/edit/1 と同じ階層を想定)
                var url =
                    UrlCom.getFuncUrl() + "/vd_delete" + option["urlSuffix"];
                // モック時は送信しない
                if (!option["isMock"]) {
                    // バリデーション
                    return axios.post(url, sendData.data, sendData.header);
                }
            })
            .then((response) => {
                // モック時はチェックしない
                if (!option["isMock"]) {
                    // バリデーション結果をチェック
                    if (!self.validateCheck($vue, response)) {
                        if (option["validationCheckbox"]) {
                            // checkbox未選択時のエラーメッセージ
                            return appDialogCom.errorCheckbox();
                        }

                        // 処理を抜ける
                        return AjaxCom.exit();
                    }
                }

                // 確認ダイアログ
                return appDialogCom.confirmDel(option["confirmStrDelete"]);
            })
            .then((flg) => {
                if (!flg) {
                    // いいえを押した場合
                    return AjaxCom.exit();
                }

                // ダイアログ
                appDialogCom.progressShow();

                // 削除
                var url = UrlCom.getFuncUrl() + "/delete" + option["urlSuffix"];

                // モック時は送信しない
                if (!option["isMock"]) {
                    // 送信
                    return axios.post(url, $vue.form);
                } else {
                    // ダミーウェイト
                    return DummyCom.wait();
                }
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

                // 完了メッセージ
                // return appDialogCom.success("削除");
                return appDialogCom.success("Has deleted");
            })
            .then(
                // 後処理を実行する
                option["afterEdit"]
            )
            .catch(AjaxCom.fail);
    }

    /**
     * 新規登録
     *
     * @param vueインスタンス
     */
    _sendNew($vue, option) {
        // 送信フォームのデータを取得する
        var sendData = this._getSendFormData($vue);

        const self = this;
        AjaxCom.getPromise()
            .then(() => {
                // バリデート(例：http://localhost:8000/sample/new と同じ階層を想定)
                var url =
                    UrlCom.getFuncUrl() + "/vd_input" + option["urlSuffix"];

                // モック時は送信しない
                if (!option["isMock"]) {
                    // ファイルアップロード時は大きいファイルが想定されるのでローディングを表示
                    // 空き時間登録のチェックボックスが多くやや時間がかかるので強制的に表示する場合も想定
                    if (sendData.upload || option["progressShow"]) {
                        // ダイアログ
                        appDialogCom.progressShow();
                    }

                    // バリデーション
                    return axios.post(url, sendData.data, sendData.header);
                }
            })
            .then((response) => {
                // モック時はチェックしない
                if (!option["isMock"]) {
                    // ファイルアップロード時は大きいファイルが想定されるのでローディングを表示
                    if (sendData.upload || option["progressShow"]) {
                        // ダイアログ
                        appDialogCom.progressHide();
                    }

                    // バリデーション結果をチェック
                    if (!self.validateCheck($vue, response)) {
                        if (option["validationCheckbox"]) {
                            // checkbox未選択時のエラーメッセージ
                            return appDialogCom.errorCheckboxRegist();
                        }
                        // 処理を抜ける
                        return AjaxCom.exit();
                    }
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

                // モック時は送信しない
                if (!option["isMock"]) {
                    // 送信
                    return axios.post(url, sendData.data, sendData.header);
                } else {
                    // ダミーウェイト
                    return DummyCom.wait();
                }
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
            .then(
                // 後処理を実行する
                option["afterEdit"]
            )
            .catch(AjaxCom.fail);
    }
}
