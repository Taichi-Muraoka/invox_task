<?php

namespace App\Consts;

/**
 * アプリケーションで使用する定数を定義したクラス
 */
class AppConst
{

    // MEMO:エイリアスに指定したので、useに入れなくても以下でも行けた。
    // VSCode上エラーになるので、可能な限りuseに指定する。
    // \AppConst::GENDER_MAN;

    //==========================
    // コードマスタ
    //==========================

    // MEMO: コードマスタの値を定義する。
    // 定数名として、きちんと名前をつけるのが面倒なので、種別_コードとした。
    // PHPDoc書けば、名称が分かるので。

    //-----------
    // Use Menu Code
    //-----------

    /**
     * Use Menu Code (使用メニューコード)
     */
    const CODE_MASTER_1 = 1;

    /**
     * Use Menu Code - Irradiation Schedule
     */
    const CODE_MASTER_1_1 = 1;

    /**
     * Use Menu Code - Customer Sample
     */
    const CODE_MASTER_1_2 = 2;

    /**
     * Use Menu Code - Irradiation Booking
     */
    const CODE_MASTER_1_3 = 3;

    /**
     * Use Menu Code - Irradiation Result
     */
    const CODE_MASTER_1_4 = 4;

    /**
     * Use Menu Code - Irradiation Customer Report
     */
    const CODE_MASTER_1_5 = 5;

    /**
     * Use Menu Code - Irradiation Result Analysis
     */
    const CODE_MASTER_1_6 = 6;

    /**
     * Use Menu Code - Customer Sample Data Management
     */
    const CODE_MASTER_1_7 = 7;

    /**
     * Use Menu Code - Irradiation Data Management
     */
    const CODE_MASTER_1_8 = 8;

    /**
     * Use Menu Code - User Data Management
     */
    const CODE_MASTER_1_9 = 9;

    /**
     * Use Menu Code - Basic Data Management
     */
    const CODE_MASTER_1_10 = 10;

    /**
     * Use Menu Code - Safety Inspection Management
     */
    const CODE_MASTER_1_11 = 11;

    /**
     * Use Menu Code - System Development Credit
     */
    const CODE_MASTER_1_12 = 12;

    //-----------
    // Company Type Code
    //-----------

    /**
     * Company Type Code (会社拠点タイプ)
     */
    const CODE_MASTER_2 = 2;

    /**
     * Company Type Code - Headquarters(本社)
     */
    const CODE_MASTER_2_1 = 1;

    /**
     * Company Type Code - Branch Office(支社)
     */
    const CODE_MASTER_2_2 = 2;

    /**
     * Company Type Code - Laboratory(ラボ)
     */
    const CODE_MASTER_2_3 = 3;

    /**
     * Company Type Code - Others(その他)
     */
    const CODE_MASTER_2_4 = 4;

    //-----------
    // Equipment Type Code
    //-----------

    /**
     * Equipment Type Code (装置タイプ)
     */
    const CODE_MASTER_3 = 3;

    /**
     * Equipment Type Code - Sample Setting Equipment(試料交換機)
     */
    const CODE_MASTER_3_1 = 1;

    /**
     * Equipment Type Code - By Hand(手作業)
     */
    const CODE_MASTER_3_2 = 2;

    //-----------
    // Container Type Code
    //-----------

    /**
     * Container Type Code
     */
    const CODE_MASTER_4 = 4;

    /**
     * Container Type Code - Aluminum Cells(アルミ缶のセル)
     */
    const CODE_MASTER_4_1 = 1;

    /**
     * Container Type Code - Tubes(チューブ)
     */
    const CODE_MASTER_4_2 = 2;

    /**
     * Container Type Code - Plastic Bags(ジップロック)
     */
    const CODE_MASTER_4_3 = 3;

    /**
     * Container Type Code - Without using a Container(容器は使わない)
     */
    const CODE_MASTER_4_4 = 4;

    /**
     * Container Type Code - Others(その他)
     */
    const CODE_MASTER_4_5 = 5;


    //-----------
    // Order Status Code
    //-----------

    /**
     * Order Status Code (案件状態コード)
     */
    const CODE_MASTER_5 = 5;

    /**
     * Order Status Code - Irradiation Planning in Progress(照射計画中)
     */
    const CODE_MASTER_5_1 = 1;

    /**
     * Order Status Code - Order Confirmed(注文確認)
     */
    const CODE_MASTER_5_2 = 2;

    /**
     * Order Status Code - Order Postponed/Pending(注文延期・保留中)
     */
    const CODE_MASTER_5_3 = 3;

    /**
     * Order Status Code - Order Cancelled(注文キャンセル)
     */
    const CODE_MASTER_5_4 = 4;

    /**
     * Order Status Code - Sample Received(試料受付完了)
     */
    const CODE_MASTER_5_5 = 5;

    /**
     * Order Status Code - Sample Returned(試料返却完了)
     */
    const CODE_MASTER_5_6 = 6;

    /**
     * Order Status Code - Closed(完了)
     */
    const CODE_MASTER_5_7 = 7;

    /**
     * Order Status Code - サブコード 登録時に表示するもの
     */
    const CODE_MASTER_5_SUB_0 = 0;

    /**
     * Order Status Code - 汎用項目1 選択削除検索に表示するもの
     */
    const CODE_MASTER_5_GEN_0 = 0;

    //-----------
    // Sample Code Status Code
    //-----------

    /**
     * Sample Code Status Code (試料状態コード)
     */
    const CODE_MASTER_6 = 6;

    /**
     * Sample Code Status Code - Registration Enrollment(登録済　（Cus Samp. Registの第二画面登録）)
     */
    const CODE_MASTER_6_1 = 1;

    /**
     * Sample Code Status Code - Booking Enrollment(予約済　（Booking RegistのCus Sampl登録）)
     */
    const CODE_MASTER_6_2 = 2;

    /**
     * Sample Code Status Code - Irradiation Incomplete(照射未完了（Ir-Registの画面により登録）)
     */
    const CODE_MASTER_6_3 = 3;

    /**
     * Sample Code Status Code - Irradiation Complete(照射完了（Ir-Registの画面により登録）)
     */
    const CODE_MASTER_6_4 = 4;

    /**
     * Sample Code Status Code サブコード
     */
    const CODE_MASTER_6_SUB_0 = 0;

    /**
     * Sample Code Status Code 汎用項目
     */
    const CODE_MASTER_6_GEN_ITEM1_1 = 0;

    //-----------
    // Equipment Area Type Code
    //-----------

    /**
     * Equipment Area Type Code (装置エリアタイプ)
     */
    const CODE_MASTER_7 = 7;

    /**
     * Equipment Area Type Code - Control 0
     */
    const CODE_MASTER_7_0 = 0;

    /**
     * Equipment Area Type Code - Slots
     */
    const CODE_MASTER_7_1 = 1;

    /**
     * Equipment Area Type Code - Backyards
     */
    const CODE_MASTER_7_2 = 2;

    /**
     * Equipment Area Type Code - Premium Seats
     */
    const CODE_MASTER_7_3 = 3;

    //-----------
    // Equipment Type to Area Type Mapping Code
    // 1: 試料交換機
    //  sub ⇒ Slots
    //  sub ⇒ Backyards
    //  sub ⇒ Premiam Seats
    // 2 :手作業
    //  sub ⇒ By Hand
    //-----------

    /**
     * Equipment Type to Area Type Mapping Code (装置タイプコード)
     */
    const CODE_MASTER_8 = 8;

    /**
     * Equipment Type to Area Type Mapping Code - Sample Setting Equipment (試料交換機のSlots)
     */
    const CODE_MASTER_8_1 = 1;

    /**
     * Equipment Type to Area Type Mapping Code - Sample Setting Equipment (試料交換機のBackyards)
     */
    const CODE_MASTER_8_2 = 2;

    /**
     * Equipment Type to Area Type Mapping Code - Sample Setting Equipment (試料交換機のPremiam Seats)
     */
    const CODE_MASTER_8_3 = 3;

    /**
     * Equipment Type to Area Type Mapping Code - By Hand (手作業)
     */
    const CODE_MASTER_8_4 = 4;

    /**
     * Equipment Type to Area Type Mapping Code - サブコード 試料交換機
     */
    const CODE_MASTER_8_SUB_1 = 1;

    /**
     * Equipment Type to Area Type Mapping Code - サブコード 手作業
     */
    const CODE_MASTER_8_SUB_2 = 2;

    //-----------
    // Irradiation Result Status Code
    //-----------

    /**
     * Irradiation Result Status Code (照射結果状態)
     */
    const CODE_MASTER_9 = 9;

    /**
     * Irradiation Result Status Code - Completed as Order
     */
    const CODE_MASTER_9_1 = 1;

    /**
     * Irradiation Result Status Code - Incompleted as Order
     */
    const CODE_MASTER_9_2 = 2;

    /**
     * Irradiation Result Status Code - Completed as Order 汎用項目１
     */
    const CODE_MASTER_9_GEN_ITEM1_1 = 4;

    /**
     * Irradiation Result Status Code - Incompleted as Order 汎用項目１
     */
    const CODE_MASTER_9_GEN_ITEM1_2 = 3;

    //-----------
    // Irradiation Result Beam Line Status Code
    //-----------

    /**
     * Irradiation Result Beam Line Status Code (照射実施場所での照射稼働の状態)
     */
    const CODE_MASTER_10 = 10;

    /**
     * Irradiation Result Beam Line Status Code - On Time Completed
     */
    const CODE_MASTER_10_1 = 1;

    /**
     * Irradiation Result Beam Line Status Code - Partially Stopped
     */
    const CODE_MASTER_10_2 = 2;

    /**
     * Irradiation Result Beam Line Status Code - Fully Stopped
     */
    const CODE_MASTER_10_3 = 3;

    //-----------
    // Numbering Type Code
    //-----------

    /**
     * Numbering Type Code (ナンバリングタイプ)
     * order／booking／ir_result／ir_report
     */
    const CODE_MASTER_11 = 11;

    /**
     * Numbering Type Code - Order Number
     */
    const CODE_MASTER_11_1 = 1;

    /**
     * Numbering Type Code - Booking Number
     */
    const CODE_MASTER_11_2 = 2;

    /**
     * Numbering Type Code - Irradiation Result Number
     */
    const CODE_MASTER_11_3 = 3;

    /**
     * Numbering Type Code - Irradiation Result Report Number
     */
    const CODE_MASTER_11_4 = 4;

    //-----------
    // Control 0 Code
    //-----------

    /**
     * Control 0 Code
     */
    const CODE_MASTER_12 = 12;

    /**
     * Control 0 Code - No
     */
    const CODE_MASTER_12_0 = 0;

    /**
     * Control 0 Code - Yes
     */
    const CODE_MASTER_12_1 = 1;

    //-----------
    // Sample Code Type Code
    //-----------

    /**
     * Sample Code Type Code (照射対象の試料かControl0の試料か試料の種別を表すコード)
     */
    const CODE_MASTER_13 = 13;

    /**
     * Sample Code Type Code - Control 0 Sample(照射非対象試料。予約時線量割り当て不能。（Ctl Yesの場合はBooking表示・Noの場合はBooking非表示制御）)
     */
    const CODE_MASTER_13_0 = 0;

    /**
     * Sample Code Type Code - Irradiation Target Sample(照射対象試料。予約時線量割り当て可能)
     */
    const CODE_MASTER_13_1 = 1;

    //-----------
    // Chromosome Volume Unit Code（染色体の体積の単位を表すコード）
    //-----------
    const CODE_MASTER_14 = 14;

    /**
     * Chromosome Volume Unit Code (染色体の体積の単位：μm)
     */
    const CODE_MASTER_14_1 = 1;

    /**
     * Chromosome Volume Unit Code (染色体の体積の単位：nm³)
     */
    const CODE_MASTER_14_2 = 2;

    /**
     * Chromosome Volume Unit Code (染色体の体積の単位：pm³)
     */
    const CODE_MASTER_14_3 = 3;

    /**
     * Chromosome Volume Unit Code (染色体の体積の単位：mm³)
     */
    const CODE_MASTER_14_4 = 4;

    //-----------
    // Genome Length Unit Code（ゲノムの長さの単位を表すコード）
    //-----------
    const CODE_MASTER_15 = 15;

    /**
     * Genome Length Unit Code (ゲノムの長さの単位：bp)
     */
    const CODE_MASTER_15_1 = 1;

    /**
     * Genome Length Unit Code (ゲノムの長さの単位：kb)
     */
    const CODE_MASTER_15_2 = 2;

    /**
     * Genome Length Unit Code (ゲノムの長さの単位：Mb)
     */
    const CODE_MASTER_15_3 = 3;

    /**
     * Genome Length Unit Code (ゲノムの長さの単位：Gb)
     */
    const CODE_MASTER_15_4 = 4;

    //-----------
    // Irradiation Result Registration Status
    //-----------
    const CODE_MASTER_16 = 16;

    /**
     * Not Yet Registered
     */
    const CODE_MASTER_16_0 = 0;

    /**
     * Registered
     */
    const CODE_MASTER_16_1 = 1;

    //-----------
    // Irradiation Date Stauts
    //-----------
    const CODE_MASTER_17 = 17;

    /**
     * TBA
     */
    const CODE_MASTER_17_0 = 0;

    /**
     * Decided
     */
    const CODE_MASTER_17_1 = 1;

    //-----------
    // Irradiation Report Issued Status Code
    //-----------
    const CODE_MASTER_18 = 18;

    /**
     * Not Report Issued
     */
    const CODE_MASTER_18_0 = 0;

    /**
     * Report Issued
     */
    const CODE_MASTER_18_1 = 1;

    //-----------
    // Application Status Code
    //-----------
    const CODE_MASTER_19 = 19;

    /**
     * Not Started
     */
    const CODE_MASTER_19_0 = 0;

    /**
     * In Progress
     */
    const CODE_MASTER_19_1 = 1;

    /**
     * Closed
     */
    const CODE_MASTER_19_2 = 2;

    //-----------
    // Alert Sending Status Code
    //-----------
    const CODE_MASTER_20 = 20;

    /**
     * Disabled
     */
    const CODE_MASTER_20_0 = 0;

    /**
     * Enabled
     */
    const CODE_MASTER_20_1 = 1;

    //-----------
    // Approval Status Code
    //-----------
    /**
     * Approval Status Code
     */
    const CODE_MASTER_21 = 21;

    /**
     * Not Approved
     */
    const CODE_MASTER_21_0 = 0;

    /**
     * Approved
     */
    const CODE_MASTER_21_1 = 1;

    //-----------
    // Batch Status
    //-----------
    const CODE_MASTER_22 = 22;

    /**
     * Successful Completion
     */
    const CODE_MASTER_22_0 = 0;

    /**
     * Error Completion
     */
    const CODE_MASTER_22_1 = 1;

    /**
     * Running
     */
    const CODE_MASTER_22_99 = 99;

    //-----------
    // Batch Type
    //-----------
    const CODE_MASTER_23 = 23;

    /**
     * Daily Batch Safety Inspection Equipment Alert Email
     */
    const CODE_MASTER_23_1 = 1;

    /**
     * Daily Batch Database Backup
     */
    const CODE_MASTER_23_2 = 2;

    //-----------
    // Ascending Descending Sort
    //-----------
    const CODE_MASTER_24 = 24;

    /**
     * Ascending
     */
    const CODE_MASTER_24_1 = 1;

    /**
     * Descending
     */
    const CODE_MASTER_24_2 = 2;

    //-----------
    // Login Success
    //-----------
    const CODE_MASTER_25 = 25;

    /**
     * Failed
     */
    const CODE_MASTER_25_0 = 0;

    /**
     * Succeeded
     */
    const CODE_MASTER_25_1 = 1;

    //-----------
    // Log Type
    //-----------
    const CODE_MASTER_26 = 26;

    /**
     * All
     */
    const CODE_MASTER_26_1 = 1;

    /**
     * Error
     */
    const CODE_MASTER_26_2 = 2;

    //==========================
    // アカウント情報
    //==========================

    // MEMO: アカウント情報の値を定義する。
    // 定数名として、きちんと名前をつけるのが面倒なので、種別_コードとした。
    // PHPDoc書けば、名称が分かるので。

    //-----------
    // パスワード初期化
    //-----------

    /**
     * パスワード初期化	不要
     */
    const ACCOUNT_PWRESET_0 = 0;

    /**
     * パスワード初期化	必要
     */
    const ACCOUNT_PWRESET_1 = 1;

    //==========================
    // bool
    //==========================
    /**
     * bool型 true
     */
    const BOOL_TRUE = 1;

    /**
     * bool型 false
     */
    const BOOL_FALSE = 0;

    //-----------
    // Irr Equipment Master (照射装置マスタ) 表示値
    //-----------
    /**
     * Slot,Backyardの最小・最大数
     */
    const IRR_EQUIP_PART_MIN = 0;
    const IRR_EQUIP_PART_MAX = 30;
    /**
     * Slot Noの最小・最大数
     */
    const IRR_EQUIP_SLOT_NUMBER_MIN = 0;
    const IRR_EQUIP_SLOT_NUMBER_MAX = 99;
    /**
     * Backyard,Premium SeatのPer Beamの最小・最大数
     */
    const IRR_EQUIP_BEAM_MIN = 1;
    const IRR_EQUIP_BEAM_MAX = 5;
}
