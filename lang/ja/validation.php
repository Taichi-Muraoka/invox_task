<?php

// 公式より取得
// https://readouble.com/laravel/7.x/ja/validation-php.html
return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | 以下の言語行はバリデタークラスにより使用されるデフォルトのエラー
    | メッセージです。サイズルールのようにいくつかのバリデーションを
    | 持っているものもあります。メッセージはご自由に調整してください。
    |
    */

    // システム共通で、不正なリクエストなど、ありえないデータの場合は以下で統一
    'invalid_input'        => '不正な値です。',
    // システム共通で、エラーが発生した際に応答結果メッセージとして返却
    'invalid_error'        => 'エラーが発生しました。',
    // システム共通で、データの重複の場合のエラー（キーの重複）
    'duplicate_data'        => '既に登録済みです。',
    // システム共通で、データの重複の場合のエラー（メールの重複）
    'duplicate_email'        => '既に登録済みのメールアドレスです。',

    // 取り込みファイルが正しくない場合のエラー
    'invalid_file'        => '正しくないファイルです。',
    // ファイルの読み込みに失敗した場合のエラー
    'read_file_error'        => 'ファイルの読み込みに失敗しました。',

    // 1件以上の存在チェックの場合のエラー
    'array_required'        => '1件以上入力してください。',

    // バッチ処理が実行中の場合のエラー
    'already_running'        => '現在別のバッチ処理を実行中のため、実行できません。',

    // 以下、デフォルトバリデーション
    // 今回は、項目の下にエラーメッセージが表示されるので、:attributeの指定は不要としてすべて削除した
    // ざっと決めたので、おかしい文言は後で修正する
    'accepted'             => '承認してください。',
    'active_url'           => '有効なURLではありません。',

    // 主に日付のチェック
    'after'                => '開始より後の日付を指定してください。',
    'after_or_equal'       => '開始以降の日付を指定してください。',
    'after_tomorrow'       => '現在日の翌日以降の日付を指定してください。',

    'alpha'                => 'アルファベットのみがご利用できます。',
    'alpha_dash'           => 'アルファベットとダッシュ(-)及び下線(_)がご利用できます。',
    'alpha_num'            => 'アルファベット数字がご利用できます。',
    'array'                => '配列でなくてはなりません。',
    'before'               => ':dateより前の日付をご利用ください。',
    'before_or_equal'      => ':date以前の日付をご利用ください。',
    'before_today'         => '現在日時より前の日時を指定してください。',
    'between'              => [
        'numeric' => ':minから:maxの間で指定してください。',
        'file'    => ':min kBから、:max kBの間で指定してください。',
        'string'  => ':min文字から、:max文字の間で指定してください。',
        'array'   => ':min個から:max個の間で指定してください。',
    ],
    'boolean'              => 'trueかfalseを指定してください。',
    'confirmed'            => '確認と一致していません。',
    'date'                 => '有効な日付を指定してください。',
    'date_equals'          => ':dateと同じ日付けを指定してください。',
    'date_format'          => ':format形式で指定してください。',
    'different'            => '重複のため指定できません。',
    'digits'               => '数字:digits桁で指定してください。',
    'digits_between'       => '数字で:min桁から:max桁の間で指定してください。',
    'dimensions'           => '図形サイズが正しくありません。',
    'distinct'             => '異なった値を指定してください。',
    'email'                => '有効なメールアドレスを指定してください。',
    'ends_with'            => ':valuesのどれかで終わる値を指定してください。',
    'exists'               => '選択された項目は正しくありません。',
    'file'                 => 'ファイルを指定してください。',
    'filled'               => '値を指定してください。',
    'gt'                   => [
        'numeric' => ':valueより大きな値を指定してください。',
        'file'    => ':value kBより大きなファイルを指定してください。',
        'string'  => ':value文字より長く指定してください。',
        'array'   => ':value個より多くのアイテムを指定してください。',
    ],
    'gte'                  => [
        'numeric' => ':value以上の値を指定してください。',
        'file'    => ':value kB以上のファイルを指定してください。',
        'string'  => ':value文字以上で指定してください。',
        'array'   => ':value個以上のアイテムを指定してください。',
    ],
    'image'                => '画像ファイルを指定してください。',
    'in'                   => '選択された項目は正しくありません。',
    'in_array'             => ':otherの値を指定してください。',
    'integer'              => '整数で指定してください。',
    'ip'                   => '有効なIPアドレスを指定してください。',
    'ipv4'                 => '有効なIPv4アドレスを指定してください。',
    'ipv6'                 => '有効なIPv6アドレスを指定してください。',
    'json'                 => '有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':valueより小さな値を指定してください。',
        'file'    => ':value kBより小さなファイルを指定してください。',
        'string'  => ':value文字より短く指定してください。',
        'array'   => ':value個より少ないアイテムを指定してください。',
    ],
    'lte'                  => [
        'numeric' => ':value以下の値を指定してください。',
        'file'    => ':value kB以下のファイルを指定してください。',
        'string'  => ':value文字以下で指定してください。',
        'array'   => ':value個以下のアイテムを指定してください。',
    ],
    'max'                  => [
        'numeric' => ':max以下の数字を指定してください。',
        'file'    => ':max kB以下のファイルを指定してください。',
        'string'  => ':max文字以下で指定してください。',
        'array'   => ':max個以下指定してください。',
    ],
    'mimes'                => ':valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':min以上の数字を指定してください。',
        'file'    => ':min kB以上のファイルを指定してください。',
        'string'  => ':min文字以上で指定してください。',
        'array'   => ':min個以上指定してください。',
    ],
    'not_in'               => '選択された正しくありません。',
    'not_regex'            => '形式が正しくありません。',
    'numeric'              => '数字を指定してください。',
    'present'              => '存在していません。',
    'regex'                => '正しい形式を指定してください。',
    'required'             => '必須入力です。',

    //'required_if'          => ':otherが:valueの場合、この項目も指定してください。',
    //'required_unless'      => ':otherが:valuesでない場合、この項目を指定してください。',
    //'required_with'        => ':valuesを指定する場合は、この項目も指定してください。',
    //'required_with_all'    => ':valuesを指定する場合は、この項目も指定してください。',
    'required_if'          => '必須入力です。',
    'required_unless'      => '必須入力です。',
    'required_with'        => '必須入力です。',
    'required_with_all'    => '必須入力です。',

    // パスワードの必須とした。とりあえず必須なのでrequiredと同じメッセージにする。(不都合あれば検討します)
    //'required_without'     => ':valuesを指定しない場合は、この項目を指定してください。',
    'required_without'             => '必須入力です。',

    // 学校検索モーダル用のバリデーションメッセージ
    //'required_without_all' => ':valuesのどれも指定しない場合は、この項目を指定してください。',
    'required_without_all' => 'どちらか必須入力です。',
    'same'                 => 'この項目と:otherには同じ値を指定してください。',

    'size'                 => [
        'numeric' => ':sizeを指定してください。',
        'file'    => ':sizeキロバイトでなくてはなりません。',
        'string'  => ':size文字で指定してください。',
        'array'   => ':size個指定してください。',
    ],
    'starts_with'          => ':valuesのどれかで始まる値を指定してください。',
    'string'               => '文字列を指定してください。',
    'timezone'             => '有効なゾーンを指定してください。',
    'unique'               => 'この値は既に存在しています。',
    'uploaded'             => 'アップロードに失敗しました。',
    'url'                  => '正しい形式を指定してください。',
    'uuid'                 => '有効なUUIDを指定してください。',

    /*
    |--------------------------------------------------------------------------
    | 独自バリデーション
    |--------------------------------------------------------------------------
    */

    // 半角英数字の指定。
    'vd_alpha_num' => '半角英数字を指定してください。',

    // 時分の指定。'date_format:H:i'の代わり
    'vd_time' => '時:分で指定してください。',

    // パスワードの指定
    'vd_password' => '半角英数字混合で、8文字以上20文字以内で指定してください。',

    // 電話番号の指定
    'vd_telephone' => '正しい電話番号の形式（半角数字またはハイフン）で指定してください。',

    // 時刻FromTo（日付とメッセージを分けるため別にvalidation定義）
    'vd_after_time' => '開始より後の時間を指定してください。',

    // 研修管理 研修期限日
    'after_or_equal_release_date' => '公開日以降の日付を指定してください。',

    // 教師スケジュール 開催日
    'after_or_equal_today' => '現在日以降の日付を指定してください。',

    // 教師スケジュール 予定の重なり
    'schedules_overlap' => '指定日時は別の予定と重なっています。',

    // ブースの重複の場合のエラー
    'duplicate_booth' => 'ブースが空いていません。',

    // 生徒スケジュール重複の場合のエラー
    'duplicate_student' => '生徒のスケジュールが重複しています。',

    // 講師スケジュール重複の場合のエラー
    'duplicate_tutor' => '講師のスケジュールが重複しています。',

    // 教科選択の重複の場合のエラー
    'duplicate_subject' => '選択教科が重複しています。',

    // 時限と開始時刻の不整合時のエラー
    'out_of_range_period' => '開始時刻が指定時限の範囲外です。',

    // 期間の不整合時のエラー
    'out_of_range_term' => '指定期間の範囲が正しくありません。',

    // 期間の不整合時のエラー
    'out_of_range_regist_term' => '登録期間外です。',

    // 選択件数のエラー
    'invalid_count_of_select' => '選択件数が正しくありません。',

    // 削除時に入力値変更不可のエラー
    'delete_cannot_change' => '削除時は変更しないでください。',

    // 会員編集 休塾開始日のエラー
    'before_or_equal_today' => '現在日以前の日付を指定してください。',

    // 会員編集 会員ステータス変更時のエラー
    'status_cannot_change' => 'このステータスへは変更できません。',

    // 会員編集 会員ステータス「退会処理中」「退会済」に変更時のエラー
    'status_cannot_change_leave' => '退会処理は退会登録画面から行なってください。',

    // 会員編集・退会処理 退会日が入会日より前のエラー
    'student_leave_after_or_equal_enter_date' => '退会日は入会日以降の日付を指定してください。',

    // 講師編集 講師ステータス「退職処理中」「退職済」に変更時のエラー
    'status_cannot_change_retirement' => '退職処理は退職登録画面から行なってください。',

    // 講師編集・退職処理 退職日が勤務開始日より前のエラー
    'tutor_leave_after_or_equal_enter_date' => '退職日は勤務開始日以降の日付を指定してください。',

    // 振替調整希望日・時限 重複
    'preferred_datetime_distinct' => '異なる希望日・時限を指定してください。',

    // 振替調整希望日・時限 振替対象授業と重複エラー
    'preferred_datetime_same' => '振替依頼をする授業と異なる希望日・時限を指定してください。',

    // 振替調整希望日・時限 フリー入力 片方のみ入力のエラー
    'preferred_input_reqired' => '日・時限の両方を指定してください。',

    // 振替調整希望日 フリー入力 振替対象期間範囲エラー
    'preferred_date_out_of_range' => '振替可能な期間の範囲外です。範囲内の日付を指定してください。',

    // 振替調整希望日 フリー入力 休業日エラー
    'preferred_date_closed' => '休業日です。他の日付を指定してください。',

    // 振替調整承認 承認時 希望日未選択エラー
    'preferred_approval_not_select' => '承認する場合、振替希望日を選択してください。',

    // 振替調整承認 希望日選択時 承認ステータスエラー
    'preferred_status_not_apply' => '振替希望日を選択した場合は、ステータスを承認で送信してください。',

    // 時限のエラー
    'invalid_period' => '有効な時限ではありません。',

    // 日付のエラー
    'invalid_date_cannot_select' => '選択できない日付です。',

    // 振替日・時限 振替対象授業と重複エラー
    'transfer_lesson_datetime_same' => '振替元の授業と異なる日・時限を指定してください。',

    // ブースの重複の場合のエラー
    'transfer_delete_duplicate_booth' => '振替元のブースが埋まっているため、振替依頼の取消しができません。',

    // 生徒スケジュール重複の場合のエラー
    'transfer_delete_duplicate_student' => '生徒のスケジュールが重複しているため、振替依頼の取消しができません。',

    // 講師スケジュール重複の場合のエラー
    'transfer_delete_duplicate_tutor' => '講師のスケジュールが重複しているため、振替依頼の取消しができません。',

    // 講師授業集計 授業日期間指定エラー
    'target_date_term' => '授業日は６ヵ月以内で指定してください。',

    // 面談希望日時 現在日以前エラー
    'after_or_equal_time' => '現在日時より後の日時を指定してください。',

    // 保護者のメールアドレスが登録されていないエラー
    'unregistered_par_email' => '保護者のメールアドレスが登録されていません。',

    // 小数入力許可項目の指定
    'vd_decimal' => '整数または小数点以下:vdDecimal桁までの小数を指定してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | "属性.ルール"の規約でキーを指定することでカスタムバリデーション
    | メッセージを定義できます。指定した属性ルールに対する特定の
    | カスタム言語行を手早く指定できます。
    |
    */

    'custom' => [
        '属性名' => [
            'ルール名' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション属性名
    |--------------------------------------------------------------------------
    |
    | 以下の言語行は、例えば"email"の代わりに「メールアドレス」のように、
    | 読み手にフレンドリーな表現でプレースホルダーを置き換えるために指定する
    | 言語行です。これはメッセージをよりきれいに表示するために役に立ちます。
    |
    */

    'attributes' => [

        // ログイン
        'email' => 'メールアドレス',
        'password' => 'パスワード',

    ],
];
