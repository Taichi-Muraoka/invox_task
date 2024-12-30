"use strict";

/*
 * defaultWeekカレンダー
 */
export default class TimelineCalendar {

    // カレンダー(一週間分配列で保持)
    _calendar = [];

    /**
     * コンストラクタ
     */
    constructor() {

    }

    /*
     * 作成
     */
    create(initDateText, startDate, endDate, start_time, end_time) {
        var initDate;
        if (!ValueCom.isEmpty(initDateText)) {
            initDate = new Date(initDateText);
        }

        // ID
        var calendarId = "calendar";

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById(calendarId);

        this._calendar = new Calendar(calendarEl, {
            // license key for premium features
            schedulerLicenseKey: "0477382314-fcs-1699842877",
            initialView: "resourceTimeGridDay",
            // カレンダーの表示範囲指定
            validRange:  {
                start: startDate,
                end: endDate
            },
            datesSet: this._dateChangeFunc,
            initialDate: initDate,
            headerToolbar: {
                left: "",
                center: "title",
                right: "prev,next",
            },
            locale: "en",
            contentHeight: "auto",
            stickyFooterScrollbar: true,
            stickyHeaderDates: true,
            dayMinWidth: 150,
            selectable: false,
            selectMirror: false,
            navLinks: true,
            // リソース（ブース）の読み込み処理
            resources: this._resourceFunc,
            // スケジュールデータの読み込み処理
            events: this._eventFunc,
            // イベントクリック
            eventClick: this._eventClick,
            selectable: true,
            // Viewエリアクリック
            select: this._selectFunc,
            eventDisplay: "block",
            eventTimeFormat: {
                hour: "2-digit",
                minute: "2-digit",
                meridiem: false,
                hour12: false,
            },
            slotMinTime: start_time,
            slotMaxTime: end_time,
            allDaySlot: false,
            slotDuration: "00:30:00",
            slotLabelFormat: {
                hour: "2-digit",
                minute: "2-digit",
                meridiem: false,
                hour12: false,
            },
            eventContent: function (info) {
                return { html: info.event.title };
            },
            eventTextColor: "white",
        });

        this._calendar.render();
    }

    /*
     * 再描画
     */
    refetchEvents() {
        this._calendar.refetchResources();
        this._calendar.refetchEvents();
    }

    /**
     * リソース表示
     *
     * @param info
     * @param successCallback
     * @param failureCallback
     */
    _resourceFunc = (info, successCallback, failureCallback) => {
        // カレンダーのカードタグのID
        var cardId = "#card-calendar";

        $.when()
            .then(() => {
                // カードのローディング開始
                FormCom.loadingForCardOn(cardId);
                // カードカレンダーの中のHidden値を取得。会員管理のように子画面にカレンダーがある場合
                var formData = FormCom.getFormArrayData(cardId);

                // カレンダーの条件を送信
                var sendData = Object.assign(formData, {});

                // 詳細データを取得
                var url = UrlCom.getFuncUrl() + "/get_equipment";
                return axios.post(url, sendData);
            })
            .then((response) => {
                // コールバックで更新(eventプロパティにセットする)
                successCallback(response.data);

                // カードのローディング終了
                FormCom.loadingForCardOff(cardId);
            })
            .fail(AjaxCom.fail);
    };

    /**
     * イベント表示
     *
     * @param info
     * @param successCallback
     * @param failureCallback
     */
    _eventFunc = (info, successCallback, failureCallback) => {
        // カレンダーのカードタグのID
        var cardId = "#card-calendar";

        $.when()
            .then(() => {
                // カードのローディング開始
                FormCom.loadingForCardOn(cardId);
                // カードカレンダーの中のHidden値を取得。会員管理のように子画面にカレンダーがある場合
                var formData = FormCom.getFormArrayData(cardId);

                // カレンダーの条件を送信
                var sendData = Object.assign(formData, {
                    start: info.start.valueOf(),
                    end: info.end.valueOf(),
                    // day: idx,
                });

                // 詳細データを取得
                var url = UrlCom.getFuncUrl() + "/get_calendar";
                return axios.post(url, sendData);
            })
            .then((response) => {
                // コールバックで更新(eventプロパティにセットする)
                successCallback(response.data);

                // カードのローディング終了
                FormCom.loadingForCardOff(cardId);
            })
            .fail(AjaxCom.fail);
    };

    /**
     * イベントクリックイベント
     *
     * @param e
     */
    _eventClick = (e) => {
        // カレンダーのカードタグのID
        var cardId = "#card-calendar";
        var formData = FormCom.getFormArrayData(cardId);
        var url = UrlCom.getFuncUrl() + "/sample" +
        "/" +
        e.event.id;
        location.href = url;
    };

    /**
     * Viewエリアクリック
     *
     * @param info
     */
    _selectFunc = (info) => {
        // カレンダーのカードタグのID
        var cardId = "#card-calendar";
        var formData = FormCom.getFormArrayData(cardId);

        // スケジュール取得
        var schedule_start_date = new Date(formData.irr_local_datetime_from);
        schedule_start_date.setHours(schedule_start_date.getHours() + 2);
        var schedule_end_date = new Date(formData.irr_local_datetime_to);
        // 選択された日時取得
        var info_start = new Date(moment(info.start).format("YYYY-MM-DD HH:mm"));
        var info_end = new Date(moment(info.end).format("YYYY-MM-DD HH:mm"));
        var regist_auth = formData.regist_auth;

        // 有効範囲が選択された場合のみ
        if ((schedule_start_date <= info_start && info_start <= schedule_end_date) && (schedule_start_date <= info_end && info_end <= schedule_end_date)) {
            // 登録権限がある場合のみ
            if (regist_auth == true) {
                var url = UrlCom.getFuncUrl() +
                    "/area/new" +
                    "/" +
                    formData.booking_id +
                    "/" +
                    info.resource._resource.id +
                    "/" +
                    moment(info.start).format("YYYY-MM-DD HH:mm") +
                    "/" +
                    moment(info.end).format("YYYY-MM-DD HH:mm");
                location.href = url;
            }
            // 登録権限がない場合はダイアログを出す
            else {
                appDialogCom.registAuthSelectTimeLine();
            }
        }
        // 有効範囲外を選択された場合はダイアログを出す
        else {
            appDialogCom.alertOutRangeSelectTimeLine();
        }
    };
}
