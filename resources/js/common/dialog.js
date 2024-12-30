"use strict";

/*
 * ダイアログの共通処理
 * bootboxを使用。jQuery依存
 */
export default class DialogCom {
    /**
     * コンストラクタ
     */
    constructor() {
        // 閉じるために変数に格納する
        this.dialogProgress = null;

        // アラートを複数あげないように対応する。(主にエラーを考慮)
        this.dialogAlert = null;
    }

    /**
     * 処理中ダイアログの表示
     * モーダルではなく、全画面を制御
     */
    // 閉じるために変数に格納する
    progressShow() {
        if (!this.dialogProgress) {
            this.dialogProgress = bootbox.dialog({
                message:
                    '<div class="overlay-spin"><i class="fas fa-2x fa-circle-notch fast-spin"></i></div>' +
                    '<div class="overlay-text">Prosessing..</div>',
                animate: false,
                centerVertical: true,
                closeButton: false,
                size: "small",
            });
        }
    }

    /**
     * 処理中ダイアログを閉じる
     */
    progressHide() {
        if (this.dialogProgress) {
            this.dialogProgress.modal("hide");
            this.dialogProgress = null;
        }
    }

    /**
     * アラートの表示
     */
    alert(msg, size, title) {
        // 既にアラートがある場合は、何もしない
        if (this.dialogAlert) {
            return;
        }

        if (!size) {
            size = "normal";
        }

        var deferred = new $.Deferred();
        var self = this;

        this.dialogAlert = bootbox.alert({
            title: title,
            size: size,
            centerVertical: true,
            message: msg,
            animate: false,
            closeButton: false,
            callback: function (result) {
                self.dialogAlert = null;
                // OK/Canceのフラグを返却
                deferred.resolve(result);
            },
        });

        return deferred.promise();
    }

    /**
     * 確認ダイアログの表示
     */
    confirm(msg, conf, size, cancelOff) {
        // 既にアラートがある場合は、何もしない
        if (this.dialogAlert) {
            return;
        }

        // confirmボタン
        if (!conf) {
            conf = {
                label: "OK",
            };
        }

        var cancel = {};
        if (!cancelOff) {
            cancel = {
                label: "Cancel",
            };
        }

        if (!size) {
            size = "normal";
        }

        var deferred = new $.Deferred();
        var self = this;

        this.dialogAlert = bootbox.confirm({
            size: size,
            centerVertical: true,
            message: msg,
            animate: false,
            closeButton: false,
            swapButtonOrder: true,
            buttons: {
                confirm: conf,
                cancel: cancel,
            },
            callback: function (result) {
                self.dialogAlert = null;
                // OK/Canceのフラグを返却
                deferred.resolve(result);
            },
        });

        return deferred.promise();
    }

    /**
     * 送信確認ダイアログの表示
     */
    confirmSend(msg) {
        if (!msg) {
            // msg = "送信";
            msg = "Are you sure you want to send it?";
        }
        // return this.confirm(msg + "してもよろしいですか？", {
        return this.confirm(msg, {
            //label: msg
        });
    }

    // /**
    //  * 更新確認ダイアログの表示
    //  */
    // confirmUpd() {
    //     return this.confirm("保存してもよろしいですか？", {
    //         label: "保存"
    //     });
    // }

    /**
     * 削除確認ダイアログの表示
     */
    confirmDel(msg) {
        if (!msg) {
            // msg = "削除";
            msg = "Are you sure you want to delete it?";
        }
        // return this.confirm(msg + "してもよろしいですか？", {
        return this.confirm(msg, {
            //label: "削除",
            className: "btn-danger",
        });
    }

    /**
     * 成功ダイアログの表示
     */
    success(msg) {
        if (!msg) {
            // msg = "完了";
            msg = "Has completed";
        }
        // msg = msg + "しました";
        return this.alert(msg);
    }

    /**
     * 削除確認ダイアログの表示
     */
    errorCheckbox() {
        return this.alert(
            // "Deleteをクリックする前に、少なくとも1つの削除チェックを選択してください。"
            "Please select at least one delete check before clicking Delete."
        );
    }

    /**
     * 登録確認ダイアログの表示（チェックボックス用）
     */
    errorCheckboxRegist() {
        return this.alert(
            // "Registerをクリックする前に、少なくとも1つのチェックを選択してください。"
            "Please select at least one check before clicking Register."
        );
    }

    /**
     * 紐づいた情報を削除
     */
    errorAllDelete() {
        return this.alert(
            // "Deleteをクリックする前に、アサインされた試料データをすべて削除してください。"
            "Deletion is not allowed because some sample codes have been booked according to the dose [Gy] in this equipment's area.<br>If you need to delete it, please delete all sample codes in this equipment's area first."
        );
    }

    /**
     * master削除エラーダイアログの表示
     */
    errorMasterDelete(dataName) {
        return this.alert(
            // "関連するxxデータが既に存在するため、削除は許可されません。"
            "Deletion is not allowed due to existing related " +
                dataName +
                " data."
        );
    }

    /**
     * master削除エラーダイアログの表示
     * alert_userデータを確認するbeam line専用
     */
     errorMasterDeleteAlertUser() {
        return this.alert(
            "Deletion is not allowed due to an existing related Beam Line in the Alert User master."
        );
    }

    /**
     * 報告書PDFダウンロードダイアログの表示
     */
    confirmPdf(reportNo, pdfEnglish, pdfIssue) {
        if (pdfEnglish && pdfIssue) {
            return this.confirm(
                reportNo +
                    " has been issued. The PDF can also be output. Are you sure you want to output the PDF now?"
            );
        } else if (!pdfEnglish && pdfIssue) {
            return this.confirm(
                reportNo +
                    "が発行されました。PDFも出力できます。このままPDFを出力してよろしいですか？"
            );
        } else if (pdfEnglish && !pdfIssue) {
            return this.confirm(
                reportNo +
                    " has been updated. The PDF can also be output. Are you sure you want to output the PDF now?"
            );
        } else if (!pdfEnglish && !pdfIssue) {
            return this.confirm(
                reportNo +
                    "が更新されました。PDFも出力できます。このままPDFを出力してよろしいですか？"
            );
        }
    }

    /**
     * Sample Copyダイアログの表示 チェックボックス未選択
     */
    alertSampleCopyNoCheck() {
        return this.alert(
            // "コピーするために少なくとも1つの試料を選択してください。"
            "Please select at least one sample to copy."
        );
    }

    /**
     * Sample Copyダイアログの表示 空白行なし
     */
    alertSampleCopyNoSpace() {
        return this.alert(
            // Sample Info.の数が25の制限を超えていることに注意ください。
            "Please note that the number of Sample Info. is over the limit of 25 records."
        );
    }

    /**
     * Sample Copyダイアログの表示 コピー実行
     */
    confirmSampleCopy() {
        return this.confirm(
            // "選択した試料データをコピーして画面に反映します。続行してもよろしいですか？"
            "The selected sample data will be copied and reflected on the screen. Do you want to continue?"
        );
    }

    /**
     * Sample Copyダイアログの表示 再検索
     */
    confirmSearchSampleAgain() {
        return this.confirm(
            // "選択したデータがあります。再度検索を行うと、チェックされた選択データはクリアされます。本当に再検索を行いますか？"
            "There is selected data. If you search again, the selected data will be cleared. Do you really want to search again?"
        );
    }

    /**
     * タイムラインで無効な範囲選択時
     */
    alertOutRangeSelectTimeLine() {
        return this.alert(
            // "指定された時間は許可されていません。指定した期間の開始時間または終了時間のどちらかが、Beam Lineスケジュールの[現地時間（開始）]から[現地時間（終了）]の範囲外です。"
            "The specified time is not allowed.<br>Either the start time or the end time of the specified period is outside the range from [Local Time(From)] to [Local Time(To)] in the Beam Line Schedule."
        );
    }

    /**
     * タイムライン選択時に登録権限がない場合
     */
    registAuthSelectTimeLine() {
        return this.alert(
            "This action is not allowed. Insufficient role permissions."
        );
    }
}
