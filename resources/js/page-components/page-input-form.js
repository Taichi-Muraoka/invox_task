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

        // モックかどうか。通信処理は行われない
        if (option["isMock"] == undefined) {
            option["isMock"] = false;
        }

        // 確認ダイアログの文言(新規登録)
        if (option["confirmStrNew"] == undefined) {
            // option["confirmStrNew"] = "送信";
            option["confirmStrNew"] = "";
        }
        // 確認ダイアログの文言(更新)
        if (option["confirmStrEdit"] == undefined) {
            // option["confirmStrEdit"] = "送信";
            option["confirmStrEdit"] = "";
        }

        // 編集完了後の処理(登録・変更・削除後)
        if (option["afterEdit"] == undefined) {
            option["afterEdit"] = () => {};
        }

        // 削除完了後の処理(削除後)
        if (option["afterDelete"] == undefined) {
            option["afterDelete"] = false;
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

        // checkbox未選択のバリデーションをするか
        if (option["validationCheckbox"] == undefined) {
            option["validationCheckbox"] = false;
        }

        // 紐づいた情報の削除バリデーションをするか
        if (option["validationAllDelete"] == undefined) {
            option["validationAllDelete"] = false;
        }

        // masterの削除バリデーションをするか
        if (option["validationMasterDelete"] == undefined) {
            option["validationMasterDelete"] = false;
        }

        // 報告書PDFダウンロードをするか
        if (option["pdfDownload"] == undefined) {
            option["pdfDownload"] = false;
        }

        // 報告書PDFダウンロード時のダイアログメッセージ言語
        if (option["pdfEnglish"] == undefined) {
            option["pdfEnglish"] = false;
        }

        // 報告書PDFが新規発行か
        if (option["pdfIssue"] == undefined) {
            option["pdfIssue"] = false;
        }

        // Customer Sample Registration 1かどうか
        if (option["isCustSampRegist1"] == undefined) {
            option["isCustSampRegist1"] = false;
        }

        // Customer Sample Registration 2かどうか
        if (option["isCustSampRegist2"] == undefined) {
            option["isCustSampRegist2"] = false;
        }

        // Booking Area Registrationかどうか
        if (option["isBookingAreaRegist"] == undefined) {
            option["isBookingAreaRegist"] = false;
        }

        // Beam Line Scheduleかどうか
        if (option["isBeamLineSchedule"] == undefined) {
            option["isBeamLineSchedule"] = false;
        }

        // Irr Result Registrationかどうか
        if (option["isIrrResultRegistration"] == undefined) {
            option["isIrrResultRegistration"] = false;
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
                    // 編集ボタン
                    submitEdit: function () {
                        // 変更処理
                        self._sendEdit(this, option);
                    },
                    // 削除ボタン
                    submitDelete: function () {
                        if (option["afterDelete"]) {
                            option["afterEdit"] = option["afterDelete"];
                        }
                        // 削除処理
                        self._sendDelete(this, option);
                    },
                    // バリデーションあり削除ボタン
                    submitValidationDelete: function () {
                        if (option["afterDelete"]) {
                            option["afterEdit"] = option["afterDelete"];
                        }
                        // 削除処理
                        self._sendValidationDelete(this, option);
                    },
                    // 承認送信ボタン
                    submitApproval: function () {
                        // 承認処理
                        self._sendApproval(this, option);
                    },
                    // プルダウンの変更イベントで詳細を取得
                    selectChangeGet: function (event) {
                        // プルダウン変更
                        // 選択された値(呼び出し元が直接呼ぶ可能性があるのでここでselectedを取るようにした)
                        var selected = event.target.value;
                        PageEvent.selectChangeGet(this, selected, option);
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
                    // 選択モーダル入力の取り消しボタン処理
                    modalSelectClear: function (event) {
                        // クリックされたボタンを取得
                        var button = $(event.target);
                        // data属性を取得
                        const modalButtonData = self.getDatasFromButton(button);
                        const modalSelectId = modalButtonData.modalselectid;

                        // クリア
                        this.form["text_" + modalSelectId] = "";
                        this.form[modalSelectId] = "";
                    },
                    // 小数点フォーマット
                    getDecimal: function (event) {
                        var text = $(event.target)[0].value;
                        // 入力された内容が数字かチェック
                        var isNotNumber = isNaN(text);

                        if (isNotNumber || text == "") {
                            // 数字でない or 空欄ならば処理終了
                            return;
                        }

                        // 数値型に変換
                        var formatNum = Number(text);

                        // 小数点第二位までを付与、または第三位以下を切り捨てて画面に反映
                        this.form[$(event.target)[0].id] = (
                            (Math.trunc(Math.trunc(formatNum * 1000) / 10) *
                                10) /
                            1000
                        ).toFixed(2);
                    },
                    // 東京の時間を確認する
                    getTokyoTime: function (time_zone, local_time) {
                        let tokyo_time = "";
                        // 時刻のパターン HH:mm or HH:m or H:mm or H:m
                        const time_pattern =
                            /^([01]?[0-9]|2[0-3]):[0-5]?[0-9]$/;

                        // データチェック
                        if (time_zone === "") {
                            // タイムゾーンが未設定でNG
                            // NOP
                        } else if (time_pattern.test(local_time) == false) {
                            // 時刻のフォーマットでないのでNG
                            // NOP ;
                        } else {
                            // データが不正でないとき、時刻を計算する
                            const DateTime = luxon.DateTime;
                            const dt = DateTime.fromFormat(local_time, "H:m", {
                                zone: time_zone,
                            });

                            // 日本時間に変換してフォーマット
                            tokyo_time = dt
                                .setZone("Asia/Tokyo")
                                .toFormat("HH:mm");
                        }

                        return tokyo_time;
                    },
                    // 東京の日付を確認する
                    getTokyoDate: function (time_zone, local_date) {
                        let tokyo_date = "";

                        // データチェック
                        if (!time_zone || !local_date) {
                            // タイムゾーンが未設定でNG
                            // NOP ;
                        } else {
                            // データが不正でないとき、時刻を計算する
                            const DateTime = luxon.DateTime;
                            const dt = DateTime.fromFormat(
                                local_date,
                                "yyyy-MM-dd",
                                {
                                    zone: time_zone,
                                }
                            );

                            // 日本時間に変換してフォーマット
                            tokyo_date = dt
                                .setZone("Asia/Tokyo")
                                .toFormat("dd/MM/yyyy");
                        }

                        return tokyo_date;
                    },
                    // 東京の日付時刻を確認する
                    getTokyoDateTime: function (
                        time_zone,
                        local_date,
                        local_time
                    ) {
                        let tokyo_date = "";
                        let tokyo_time = "";
                        const DateTime = luxon.DateTime;

                        // 時刻のパターン HH:mm or HH:m or H:mm or H:m
                        const time_pattern =
                            /^([01]?[0-9]|2[0-3]):[0-5]?[0-9]$/;
                        const date_pattern =
                            /^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
                        // データチェック
                        if (!time_zone) {
                            // タイムゾーンが未設定でNG
                            // NOP ;
                        } else if (time_pattern.test(local_time) == false) {
                            // 時刻のフォーマットでないのでNG
                            // NOP ;
                        } else if (date_pattern.test(local_date) == false) {
                            // 日時のフォーマットでないのでNG
                            // NOP ;
                        } else {
                            // データが不正でないとき、時刻を計算する
                            const DateTime = luxon.DateTime;
                            const datetime = `${local_date} ${local_time}`;
                            const dt = DateTime.fromFormat(
                                datetime,
                                "yyyy-MM-dd H:m",
                                {
                                    zone: time_zone,
                                }
                            );

                            // 日本時間に変換してフォーマット
                            tokyo_date = dt
                                .setZone("Asia/Tokyo")
                                .toFormat("dd/MM/yyyy");
                            tokyo_time = dt
                                .setZone("Asia/Tokyo")
                                .toFormat("HH:mm");
                        }

                        return [tokyo_date, tokyo_time];
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

        // Customer Sample Registration 1,2用
        // createメソッドで返した値を取得
        var orderId = null;
        var orderNumber = null;

        // Irradiation Booking用
        var bookingEquipmentAreaId = null;
        // Irradiation Report用
        // createメソッドで返した値を取得
        var reportId = null;
        var reportNumber = null;

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

                if (option["pdfDownload"]) {
                    // createメソッドで返した値を取得
                    reportId = response.data["report_id"];
                    reportNumber = response.data["report_number"];

                    // 報告書PDF出力確認ダイアログ
                    return appDialogCom.confirmPdf(
                        reportNumber,
                        option["pdfEnglish"],
                        option["pdfIssue"]
                    );
                } else if (option["isCustSampRegist1"]) {
                    // Customer Sample Registration 1完了メッセージ

                    // createメソッドで返した値を取得
                    orderId = response.data["order_id"];
                    orderNumber = response.data["order_number"];

                    return appDialogCom.success(
                        "Registration completed." +
                            orderNumber +
                            ' has been issued. Next, please click the "Input Detail" button and register the sample details data on the next screen.'
                    );
                } else if (option["isCustSampRegist2"]) {
                    // Customer Sample Registration 2完了メッセージ

                    // createメソッドで返した値を取得
                    orderId = response.data["order_id"];

                    return appDialogCom.success(option["completeStrNew"]);
                } else if (option["isBookingAreaRegist"]) {
                    // createメソッドで返した値を取得
                    bookingEquipmentAreaId =
                        response.data["booking_equipment_area_id"];

                    return appDialogCom.success(option["confirmStrNew"]);
                }
                // Beam Line Schedule 完了メッセージ
                else if (option["isBeamLineSchedule"]) {
                    let booking_number = response.data["booking_number"];

                    return appDialogCom.success(
                        "Registration completed." +
                            booking_number +
                            " has been issued."
                    );
                }
                // Irr Result Registration 完了メッセージ
                else if (option["isIrrResultRegistration"]) {
                    let irr_result_number = response.data["irr_result_number"];

                    return appDialogCom.success(
                        "Registration completed." +
                            irr_result_number +
                            " has been issued."
                    );
                } else {
                    // 通常完了メッセージ
                    return appDialogCom.success(option["confirmStrNew"]);
                }
            })
            .then((flg) => {
                // pdfDownloadをしない場合はここの処理はしない
                if (!option["pdfDownload"]) {
                    return;
                }

                // 報告書PDF出力確認ダイアログの OK or キャンセル の結果の処理
                if (!flg) {
                    // キャンセルを押した場合
                    return;
                }

                // ダイアログ
                appDialogCom.progressShow();

                // PDF出力
                var url =
                    UrlCom.getFuncUrl() +
                    "/pdf" +
                    option["urlSuffix"] +
                    "/" +
                    reportId;

                // 送信(URLを開く)
                // PDFをダウンロードしたいので、ajaxで呼び出さない
                UrlCom.redirect(url);
                // ダイアログ
                appDialogCom.progressHide();

                // PDFダウンロードを待つウェイト
                return DummyCom.sleep(3000);
            })
            .then(() => {
                // pdfDownloadをしない場合はここの処理はしない
                if (!option["pdfDownload"]) {
                    return;
                }

                // 完了メッセージ
                return appDialogCom.success();
            })
            .then(() => {
                if (
                    option["isCustSampRegist1"] ||
                    option["isCustSampRegist2"]
                ) {
                    // Customer Sample Registration 1,2画面遷移
                    return UrlCom.redirect(
                        UrlCom.getFuncUrl() + "/edit/" + orderId
                    );
                } else {
                    // 後処理を実行する
                    option["afterEdit"]();
                }
            })
            .then(() => {
                if (option["isBookingAreaRegist"]) {
                    // sample画面遷移
                    return UrlCom.redirect(
                        UrlCom.getFuncUrl() +
                            "/sample/" +
                            bookingEquipmentAreaId
                    );
                } else {
                    // 後処理を実行する
                    option["afterEdit"]();
                }
            })
            .catch(AjaxCom.fail);
    }

    /**
     * 編集
     *
     * @param vueインスタンス
     */
    _sendEdit($vue, option) {
        // 送信フォームのデータを取得する
        var sendData = this._getSendFormData($vue);

        // Customer Sample Registration 1,2用
        // updateメソッドで返した値を取得
        var orderId = null;

        // Irradiation Report用
        // updateメソッドで返した値を取得
        var reportId = null;
        var reportNumber = null;

        const self = this;
        AjaxCom.getPromise()
            .then(() => {
                // バリデート(例：http://localhost:8000/sample/edit/1 と同じ階層を想定)
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
                        // Beam Line Schedule Update(TotalDays）のエラーメッセージ
                        if (option["isBeamLineSchedule"]) {
                            if (
                                response.data.hasOwnProperty("irr_total_days")
                            ) {
                                let err_msg =
                                    "Cannot be updated. Booking irradiation Days that are outside the range of Irradiation Total Days.";
                                for (let msg of response.data["irr_total_days"])
                                    if (msg == err_msg) {
                                        appDialogCom.alert(msg);
                                    }
                            }
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
                    return appDialogCom.confirmSend(option["confirmStrEdit"]);
                }
            })
            .then((flg) => {
                if (!flg) {
                    // いいえを押した場合
                    return AjaxCom.exit();
                }

                // ダイアログ
                appDialogCom.progressShow();

                // 編集
                var url = UrlCom.getFuncUrl() + "/update" + option["urlSuffix"];

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

                if (option["pdfDownload"]) {
                    // updateメソッドで返した値を取得
                    reportId = response.data["report_id"];
                    reportNumber = response.data["report_number"];

                    // 報告書PDF出力確認ダイアログ
                    return appDialogCom.confirmPdf(
                        reportNumber,
                        option["pdfEnglish"],
                        option["pdfIssue"]
                    );
                } else if (
                    option["isCustSampRegist1"] ||
                    option["isCustSampRegist2"]
                ) {
                    // Customer Sample Registration 1,2完了メッセージ

                    // updateメソッドで返した値を取得
                    orderId = response.data["order_id"];

                    return appDialogCom.success(option["completeStrEdit"]);
                } else {
                    // 通常完了メッセージ
                    return appDialogCom.success(option["confirmStrEdit"]);
                }
            })
            .then((flg) => {
                // pdfDownloadをしない場合はここの処理はしない
                if (!option["pdfDownload"]) {
                    return;
                }

                // 報告書PDF出力確認ダイアログの OK or キャンセル の結果の処理
                if (!flg) {
                    // キャンセルを押した場合
                    return;
                }

                // ダイアログ
                appDialogCom.progressShow();

                // PDF出力
                var url =
                    UrlCom.getFuncUrl() +
                    "/pdf" +
                    option["urlSuffix"] +
                    "/" +
                    reportId;

                // 送信(URLを開く)
                // PDFをダウンロードしたいので、ajaxで呼び出さない
                UrlCom.redirect(url);

                // ダイアログ
                appDialogCom.progressHide();

                // PDFダウンロードを待つウェイト
                return DummyCom.sleep(3000);
            })
            .then(() => {
                // pdfDownloadをしない場合はここの処理はしない
                if (!option["pdfDownload"]) {
                    return;
                }

                // 完了メッセージ
                return appDialogCom.success();
            })
            .then(() => {
                if (
                    option["isCustSampRegist1"] ||
                    option["isCustSampRegist2"]
                ) {
                    // Customer Sample Registration 1,2画面遷移
                    return UrlCom.redirect(
                        UrlCom.getFuncUrl() + "/edit/" + orderId
                    );
                } else {
                    // 後処理を実行する
                    option["afterEdit"]();
                }
            })
            .catch(AjaxCom.fail);
    }

    /**
     * 削除
     *
     * @param vueインスタンス
     */
    _sendDelete($vue, option) {
        AjaxCom.getPromise()
            .then((response) => {
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

                if (option["pdfDownload"] && !option["pdfEnglish"]) {
                    // 日本語版 - 照射報告書削除完了メッセージ
                    return appDialogCom.success("削除しました。");
                } else {
                    // 通常完了メッセージ
                    return appDialogCom.success("Has deleted");
                }
            })
            .then(
                // 後処理を実行する
                option["afterEdit"]
            )
            .catch(AjaxCom.fail);
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
                        if (option["validationAllDelete"]) {
                            // 紐づいた情報を削除
                            return appDialogCom.errorAllDelete();
                        }
                        if (option["validationMasterDelete"]) {
                            if (response.data["alert_user"]) {
                                // responseの中にalert_userの項目があれば専用メッセージ
                                return appDialogCom.errorMasterDeleteAlertUser();
                            }

                            // master削除不可のメッセージ
                            return appDialogCom.errorMasterDelete(
                                option["dataName"]
                            );
                        }

                        // Beam Line Schedule Update(TotalDays）のエラーメッセージ
                        if (option["isBeamLineSchedule"]) {
                            let err_msg =
                                "Cannot be deleted. Dose is already booked according to this schedule.";
                            for (let msg of response.data["irr_total_days"])
                                if (msg == err_msg) {
                                    appDialogCom.alert(err_msg);
                                }
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
                return appDialogCom.success("Has deleted");
            })
            .then(
                // 後処理を実行する
                option["afterEdit"]
            )
            .catch(AjaxCom.fail);
    }

    /**
     * 承認
     *
     * @param vueインスタンス
     */
    _sendApproval($vue, option) {
        // 送信フォームのデータを取得する
        var sendData = this._getSendFormData($vue);

        const self = this;
        AjaxCom.getPromise()
            .then(() => {
                // バリデート(例：http://localhost:8000/sample/edit/1 と同じ階層を想定)
                var url =
                    UrlCom.getFuncUrl() + "/vd_approval" + option["urlSuffix"];
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
                    return appDialogCom.confirmSend(option["confirmStrEdit"]);
                }
            })
            .then((flg) => {
                if (!flg) {
                    // いいえを押した場合
                    return AjaxCom.exit();
                }

                // ダイアログ
                appDialogCom.progressShow();

                // 編集
                var url = UrlCom.getFuncUrl() + "/update" + option["urlSuffix"];

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

                // 完了メッセージ
                return appDialogCom.success(option["confirmStrEdit"]);
            })
            .then(
                // 後処理を実行する
                option["afterEdit"]
            )
            .catch(AjaxCom.fail);
    }
}
