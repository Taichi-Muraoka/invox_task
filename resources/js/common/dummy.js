"use strict";

/*
 * ダミークラス
 */
export default class DummyCom {
    /**
     * ダミー用のWait モック用
     */
    static wait() {
        var time = 5000;
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(`wait: ${time}`);
            }, time);
        });
    }

    /**
     * 照射報告書PDF用のWait
     */
     static sleep(time) {
        // var time = 5000;
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(`wait: ${time}`);
            }, time);
        });
    }
}
