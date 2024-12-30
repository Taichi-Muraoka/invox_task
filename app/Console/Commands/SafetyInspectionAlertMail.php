<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BatchMng;
use Illuminate\Support\Facades\Mail;
use App\Mail\SafetyInspectionAlert;
use Carbon\Carbon;
use App\Consts\AppConst;
use App\Models\MstAlertRules;
use App\Models\MstAlertUsers;
use App\Models\User;
use App\Models\MstIrrBeamLine;
use App\Models\MstIrrEquipment;

/**
 * 安全点検アラートメール送信 - バッチ処理
 */
class SafetyInspectionAlertMail extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:safetyInspectionAlertMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $batch_id = null;
        try {

            Log::info("Batch safetyInspectionAlertMail Start.");

            $now = Carbon::now();

            // バッチ管理テーブルにレコード作成
            $batchMng = new BatchMng;
            $batchMng->batch_type = AppConst::CODE_MASTER_23_1;
            $batchMng->start_time = $now;
            $batchMng->batch_state = AppConst::CODE_MASTER_22_99;
            $batchMng->adm_id = null;
            $batchMng->save();

            $batch_id = $batchMng->batch_id;

            // トランザクション(例外時は自動的にロールバック)
            DB::transaction(function () use ($batch_id) {

                //-------------------------
                // アラート対象日付取得
                //-------------------------
                $alertDays = [];
                // 45日後・30日後・14日後の日付を取得
                $alertDays[] = date("Y-m-d", strtotime("+45 day"));
                $alertDays[] = date("Y-m-d", strtotime("+30 day"));
                $alertDays[] = date("Y-m-d", strtotime("+14 day"));
                // 7日後の日付を取得
                $alert1Week =  date("Y-m-d", strtotime("+7 day"));

                //-------------------------
                // アラートルール情報取得
                //-------------------------
                $alertRules = MstAlertRules::select(
                    'mst_alert_rules.alert_rule_id',
                    'mst_alert_rules.beam_line_subject_to_safety_inspection_id',
                    'mst_alert_rules.current_equipment_usage_permission_date_from',
                    'mst_alert_rules.current_equipment_usage_permission_date_to',
                    'mst_irr_beam_lines.beam_line_name',
                    'mst_irr_equipments.equipment_name',
                )
                    ->selectRaw('DATEDIFF(current_equipment_usage_permission_date_to, curdate()) as diff_day')
                    ->sdLeftJoin(MstIrrBeamLine::class, 'mst_alert_rules.beam_line_subject_to_safety_inspection_id', '=', 'mst_irr_beam_lines.beam_line_id')
                    ->sdLeftJoin(MstIrrEquipment::class, 'mst_alert_rules.sample_setting_equipment_id', '=', 'mst_irr_equipments.equipment_id')
                    // Alert Sending Status Code = Enabled
                    ->where('mst_alert_rules.alert_sending_status_cd', AppConst::CODE_MASTER_20_1)
                    // 許可終了日を基準にアラート対象日に該当するもの
                    ->where(function ($orQuery) use ($alertDays, $alert1Week) {
                        $orQuery
                            ->whereIn('mst_alert_rules.current_equipment_usage_permission_date_to', $alertDays)
                            ->orWhere('mst_alert_rules.current_equipment_usage_permission_date_to', '<=', $alert1Week);
                    })
                    ->orderBy('mst_alert_rules.alert_rule_id', 'asc')
                    ->get();

                $sendCount = 0;
                // アラートルール毎の処理
                foreach ($alertRules as $alertRule) {
                    //-------------------------
                    // アラート対象ユーザのメール宛先アドレス取得
                    //-------------------------
                    $sendUsers = MstAlertUsers::select(
                        'mst_alert_users.alert_user_id',
                        'users.email',
                    )
                        ->sdJoin(User::class, 'mst_alert_users.alert_recipient_user_id', '=', 'users.user_id')
                        // Beam Line Subject to Safety Inspection ID = アラートルール情報のBeam Line Subject to Safety Inspection ID
                        ->where('mst_alert_users.beam_line_subject_to_safety_inspection_id', $alertRule->beam_line_subject_to_safety_inspection_id)
                        ->orderBy('mst_alert_users.alert_user_id', 'asc')
                        ->get();

                    if (count($sendUsers) > 0) {
                        // 対象ユーザ情報がある場合のみメール送信
                        $sendTo = $sendUsers->pluck('email');
                        //-------------------------
                        // メール送信
                        //-------------------------
                        // 送信メッセージ種別設定
                        if ($alertRule->diff_day > 0) {
                            // 許可終了日当日より前の場合
                            $mailBefore = AppConst::BOOL_TRUE;
                        } else {
                            // 許可終了日当日以降の場合
                            $mailBefore = AppConst::BOOL_FALSE;
                        }

                        // メール本文に記載する情報をセット
                        $mail_body = [
                            'mailBefore' => $mailBefore,
                            'beamLineName' => $alertRule->beam_line_name == null ? "" : $alertRule->beam_line_name,
                            'equipmentName' => $alertRule->equipment_name == null ? "" : $alertRule->equipment_name,
                            'permissionDateFrom' => $alertRule->current_equipment_usage_permission_date_from->format('d/m/Y'),
                            'permissionDateTo' => $alertRule->current_equipment_usage_permission_date_to->format('d/m/Y'),
                            'diffDay' => $alertRule->diff_day,
                        ];

                        // メール送信
                        Mail::to($sendTo)->send(new SafetyInspectionAlert($mail_body));
                        $sendCount++;
                    }
                }

                // バッチ管理テーブルのレコードを更新：正常終了
                $end = Carbon::now();
                BatchMng::where('batch_id', '=', $batch_id)
                    ->update([
                        'end_time' => $end,
                        'batch_state' => AppConst::CODE_MASTER_22_0,
                        'updated_at' => $end
                    ]);

                Log::info("Send {$sendCount} mail.");
                Log::info("safetyInspectionAlertMail Succeeded.");
            });
        } catch (\Exception  $e) {
            // バッチ管理テーブルのレコードを更新：異常終了
            $end = Carbon::now();
            BatchMng::where('batch_id', '=', $batch_id)
                ->update([
                    'end_time' => $end,
                    'batch_state' => AppConst::CODE_MASTER_22_1,
                    'updated_at' => $end
                ]);
            // この時点では補足できないエラーとして、詳細は返さずエラーとする
            Log::error($e);
        }
        return 0;
    }
}
