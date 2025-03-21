"use strict";

import { createApp, h } from "vue/dist/vue.esm-bundler";

/**
 * ページコンポーネントのスーパークラス
 */
export default class PageComponentBase {
    /**
     * Vue3コンポーネント作成
     */
    createComponent(vueApp, id) {
        const app = createApp(vueApp);

        // Vueのselect2対応。v-selectを作成し、
        // チェンジイベントを拾い、値をセット。
        app.directive("select", {
            beforeMount(el, binding, vnode) {
                $(el)
                    .select2()
                    .on("select2:select", (e) => {
                        el.dispatchEvent(
                            new Event("change", { target: e.target })
                        );
                    });
            },
        });

        // http または httpsから始まる文字列をリンクにするコンポーネント
        // 使い方：<autolink :text="変数名"></autolink>
        app.component("autolink", {
            props: ["text"],
            render() {
                // 変数が空の場合は処理なし
                if (!this.text) {
                    return;
                }

                const a = this.text.split(
                    /(https?:\/\/[\w!?:\/\+\-_~=;\.,*&@#$%\(\)\'\[\]]+)/i
                );

                const vnodes = a.map(function (x, i) {
                    if (i % 2) {
                        return h("a", { href: x }, x);
                    } else {
                        return x;
                    }
                }, this);

                return h("span", vnodes);
            },
        });

        // フィルター
        app.config.globalProperties.$filters = this._getFilters();

        app.config.compilerOptions.whitespace = "preserve";

        return app.mount(id);
    }

    /**
     * Vueで使用する共通のフィルター
     */
    _getFilters() {
        return {
            // 日月年
            formatDmy(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("DD/MM/YYYY");
                }
            },
            // 年月日
            formatYmd(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY/MM/DD");
                }
            },
            // スケジュール年月日(曜日あり)
            formatYmdDay(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY/MM/DD(dd)");
                }
            },
            // 年月日（ハイフンなし）
            formatYmdNoH(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYYMMDD");
                }
            },
            // 年月日 日時
            formatYmdHm(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY/MM/DD HH:mm");
                }
            },
            // 日月年 日時
            formatDmyHm(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("DD/MM/YYYY HH:mm");
                }
            },
            // 年月日 日時（秒）
            formatYmdHms(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY/MM/DD HH:mm:ss");
                }
            },
            // 日月年 日時（秒）
            formatDmyHms(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("DD/MM/YYYY HH:mm:ss");
                }
            },
            // 年月
            formatYm(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY/MM");
                }
            },
            // 時刻
            formatHm(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    if (date.length == 8) {
                        // 16:00:00 のようなケースに対応
                        return moment(date, "HH:mm:ss").format("HH:mm");
                    } else {
                        // datetimeの場合：2020-11-20T07:00:00.000000Z
                        return moment(date).format("HH:mm");
                    }
                }
            },
            // 金額のカンマ区切り
            toLocaleString(numString) {
                if (ValueCom.isEmpty(numString)) {
                    return "";
                } else {
                    return Number(numString).toLocaleString();
                }
            },
            // 小数点以下0切り捨て
            numberRound(numString) {
                if (ValueCom.isEmpty(numString)) {
                    return "";
                } else {
                    return Number(numString);
                }
            },
            // YYYY年MM月
            formatYmString(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("YYYY年MM月");
                }
            },
            // Y年Mか月 通塾期間・勤続年数で使用
            formatTotalMonth(num) {
                if (ValueCom.isEmpty(num)) {
                    return "";
                } else {
                    var year = Math.floor(num / 12);
                    var month = Math.floor(num % 12);
                    return year + "年" + month + "ヶ月";
                }
            },
            // 曜日
            formatWeek(date) {
                if (ValueCom.isEmpty(date)) {
                    return "";
                } else {
                    return moment(date).format("ddd");
                }
            },
        };
    }

    /**
     * ライブラリの初期化
     * Vueの初期化後じゃないとうまく読めない場合
     */
    initLibs($vue, option = {}) {
        this.initFileInput($vue, option);
        this.initSelect2($vue, option);
        this.initDatePicker($vue, option);
    }

    initFileInput($vue, option = {}) {
        //---------------------
        // bs-custom-file-input
        //---------------------

        // ファイル選択フォームのカスタマイズ
        // これがないと、ファイルを選択してもファイル名が表示されなかたりする
        bsCustomFileInput.init();

        // 取り消しボタンの挙動
        $($vue.appId + " .inputFileReset").on("click", function (element) {
            bsCustomFileInput.destroy();

            // 同じform-group内のfileを取得
            var inputFile = $(element.target)
                .parent()
                .parent()
                .find("input[type='file']")
                .get(0);

            // ファイルをクリア
            inputFile.value = "";

            // var clone = inputFile.cloneNode(false);
            // inputFile.parentNode.replaceChilld(clone, inputFile);
            // IE対応のため上記を試したがreplaceChilldをVueが拾ってくれないので無視
            inputFile.dispatchEvent(new Event("change"));

            bsCustomFileInput.init();
        });
    }

    initSelect2($vue, option = {}) {
        //---------------------
        // select2
        //---------------------

        $($vue.appId + " .select2").select2({});

        // 複数選択プルダウンの値の変更がうまく反映されないためこちらで対応
        $($vue.appId + " .select2").each(function (index, element) {
            if ($(element).attr("multiple")) {
                $(element).change(function (e) {
                    // 変更後のvalを$vue.formにセットする
                    $vue.form[element.id] = $(element).val();
                });
            }
        });
    }

    initDatePicker($vue, option = {}) {
        // datepickerイベント
        if (option["datepickerOnChange"] == undefined) {
            option["datepickerOnChange"] = ($vue, id, value) => {};
        }
        //---------------------
        // datepicker
        //---------------------

        // locale
        var localeDate = {
            format: "DD/MM/YYYY",
            format2: "YYYY-MM-DD",
            applyLabel: "Apply",
            // 今回はクリアボタンにする
            cancelLabel: "Clear",
            weekLabel: "W",
            daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            monthNames: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
        };

        // date
        $($vue.appId + " .date-picker").each(function (index, element) {
            // _xxx がカレンダーinputなので先頭1文字削除し、本当のIDを取得
            var id = element.id.substr(1);

            // フォーマットを変更してセットする：2020年11月01日
            if (!ValueCom.isEmpty($(element).val())) {
                // フォーマットを明示的に指定する
                var dateVal = moment($(element).val(), "DD/MM/YYYY");
                $(element).val(dateVal.format(localeDate.format));
                // hiddenも調整しておく
                $vue.form[id] = dateVal.format(localeDate.format2);
                // イベント発生
                option["datepickerOnChange"]($vue, id, $vue.form[id]);
            }

            $(element)
                .daterangepicker(
                    {
                        singleDatePicker: true,
                        locale: localeDate,
                        showDropdowns: true,
                        // カレンダーの範囲 システム日付の前後5年をpicker入力可とする
                        minYear: moment().subtract(5, 'years').format('YYYY'),
                        maxYear: moment().add(5, 'years').format('YYYY'),
                        minDate: '01/01/' + moment().subtract(5, 'years').format('YYYY'),
                        maxDate: '31/12/' + moment().add(5, 'years').format('YYYY'),
                        // 最初から自動で日付が入ってしまうので手動で格納
                        autoUpdateInput: false,
                        // カレンダーのポップアップ位置を自動で調整
                        // 下の方にテキストボックスがあれば、カレンダーは上にポップアップされる
                        drops: "auto",
                    }
                    // 以下だと本日日付で適用ボタン押しても呼ばれない
                    // function(start, end, label) {}
                )
                .change(function (e) {
                    // 直接入力時のカレンダーのセット

                    // カレンダーの日付の取得
                    var input = $(e.target).val();
                    if (ValueCom.isEmpty(input)) {
                        // 空白の場合
                        $(this).val("");
                        $vue.form[id] = "";
                        // イベント発生
                        option["datepickerOnChange"]($vue, id, $vue.form[id]);
                        return;
                    }

                    // DD/MM/YYYYの日付形式に対応
                    var calDate = moment($(e.target).val(), localeDate.format);
                    if (!calDate.isValid()) {
                        // 一応エラーを拾う
                        // クリア扱いにした
                        $(this).val("");
                        $vue.form[id] = "";
                        // イベント発生
                        option["datepickerOnChange"]($vue, id, $vue.form[id]);
                        // エラーダイアログ
                        // appDialogCom.alert("正しい日付を入力してください");
                        appDialogCom.alert("Please enter a valid date.");
                    } else {
                        // テキストボックスに表示用
                        // テキストボックスに表示(0埋めをしているだけ。無くても良いが一応)
                        $(e.target).val(
                            moment(calDate).format(localeDate.format)
                        );

                        // hiddenにセット
                        $vue.form[id] = moment(calDate).format(
                            localeDate.format2
                        );
                        // イベント発生
                        option["datepickerOnChange"]($vue, id, $vue.form[id]);
                    }
                })
                .on("apply.daterangepicker", function (ev, picker) {
                    // 適用ボタンクリックイベントで取得
                    $(this).val(picker.startDate.format(localeDate.format));
                    // hiddenにセット
                    $vue.form[id] = picker.startDate.format(localeDate.format2);
                    // イベント発生
                    option["datepickerOnChange"]($vue, id, $vue.form[id]);
                })
                .on("cancel.daterangepicker", function (ev, picker) {
                    // キャンセルボタンはクリアとした
                    $(this).val("");
                    $vue.form[id] = "";
                    // イベント発生
                    option["datepickerOnChange"]($vue, id, $vue.form[id]);
                });
        });
    }

    /**
     * ライブラリの初期化
     * Vueの更新(updated)の際に呼ぶ
     * select2もそうだが、Vueの更新が終わった後に初期化が必要な処理
     */
    updatedLibs($vue) {
        // お知らせ管理で動的にプルダウン(select2)を変更しているが、
        // プルダウンを選択したり、再描画すると、うまく表示できないケースがある。
        // Vue上は正しく反映しているが、select2の描画がうまく行かないようだ。
        // Vueのupdatedイベントで呼んでもらう
        $($vue.appId + " .select2").select2();
    }

    /**
     * バリデーション結果をチェックする
     */
    validateCheck($vue, response) {
        // 一旦エラーをクリア
        $vue.form_err.msg = {};
        $vue.form_err.class = {};

        // 確認モーダル用のデータの場合は無視する(confirm_modal_dataの1件だけある場合)
        if (
            Object.keys(response.data).length == 1 &&
            response.data["confirm_modal_data"]
        ) {
            // チェックしない
        }
        // エラーがあるかどうか
        else if (!(response.data.length <= 0)) {
            // バリデーションエラー
            for (const [key, value] of Object.entries(response.data)) {
                // vueにセット
                $vue.form_err.msg[key] = value;
                $vue.form_err.class[key] = true;

                // スクロール用にキーを格納する
                if ($vue.validateErrKey) {
                    $vue.validateErrKey.push(key);
                }
            }

            // Vueのupdatedでスクロールを行う(検索フォームは不要。入力フォームだけ)
            $vue.afterValidate = true;

            return false;
        }

        return true;
    }

    /**
     * ボタンのData属性を配列で取得
     */
    getDatasFromButton(button) {
        // data属性をすべて取得し送信する
        // $.dataはキャッシュするみたい。Vueで動的に変えたIdが取れなくなってしまう。
        // 以下のattrで必ず取得するが、一気に取れないのでdataのキーでループ
        const datas = $(button).data();
        const dataKeys = Object.keys(datas);
        var arrayData = {};
        for (var i = 0; i < dataKeys.length; i++) {
            const key = dataKeys[i];
            // 配列で保持 attrで取得
            arrayData[key] = $(button).attr("data-" + key);
        }

        return arrayData;
    }
}
