<?php

namespace App\Http\Controllers\Traits;

use App\Consts\AppConst;
use App\Models\CodeMaster;
use App\Models\MstBioCellClass;
use App\Models\MstCompany;
use App\Models\MstCustomer;
use App\Models\MstIrrBeamLine;
use App\Models\MstIrrOperator;
use App\Models\MstIrrEquipment;
use App\Models\MstIrrEquipmentArea;
use App\Models\MstMicroOrgSpecies;
use App\Models\MstMorphology;
use App\Models\MstPolyploid;
use App\Models\MstSampleContainer;
use App\Models\User;
use App\Models\RoleType;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

/**
 * モデル - コントローラ共通処理
 */
trait CtrlModelTrait
{

    //==========================
    // 関数名を区別するために
    // mdl(モデル)を先頭につける
    //==========================

    //------------------------------
    // プルダウン向けリストの作成
    //------------------------------

    /**
     * コードマスタからプルダウンメニューのリストを取得
     * data_typeを指定する
     *
     * @param integer $dataType
     * @param array $subCode サブコード（配列で指定）省略可
     * @param array $gen_item1 汎用項目1（配列で指定）省略可
     * @return array
     */
    protected function mdlMenuFromCodeMaster($dataType, $subCodes = null)
    {

        $query = CodeMaster::query();

        // サブコードが指定された場合絞り込み
        $query->when($subCodes, function ($query) use ($subCodes) {
            return $query->whereIn('sub_code', $subCodes);
        });

        // プルダウンリストを取得する
        return $query->select('code', 'name as value')
            ->where('data_type', $dataType)
            ->orderby('order_code')
            ->get()
            ->keyBy('code');
    }

    /**
     * コードマスタからプルダウンメニューのリストを取得
     * gen_item1で絞り込む
     * data_typeを指定する
     *
     * @param integer $dataType
     * @param array $genItem1 汎用項目1（配列で指定）省略可
     * @return array
     */
    protected function mdlMenuFromCodeMasterGenItem1($dataType, $genItem1 = null)
    {

        $query = CodeMaster::query();

        // 汎用項目1が指定された場合絞り込み
        $query->when($genItem1, function ($query) use ($genItem1) {
            return $query->whereIn('gen_item1', $genItem1);
        });

        // プルダウンリストを取得する
        return $query->select('code', 'name as value')
            ->where('data_type', $dataType)
            ->orderby('order_code')
            ->get()
            ->keyBy('code');
    }

    /**
     * コードマスタからプルダウンメニューのリストを取得
     * data_typeと、名称として取得するカラム名を指定する
     *
     * @param integer $dataType
     * @param string $colName
     * @param array $subCode サブコード（配列で指定）省略可
     * @return array
     */
    protected function mdlMenuFromCodeMasterGenItem($dataType, $colName, $subCodes = null)
    {

        $query = CodeMaster::query();

        // サブコードが指定された場合絞り込み
        $query->when($subCodes, function ($query) use ($subCodes) {
            return $query->whereIn('sub_code', $subCodes);
        });

        // プルダウンリストを取得する
        return $query->select('code', $colName . ' as value')
            ->where('data_type', $dataType)
            ->orderby('order_code')
            ->get()
            ->keyBy('code');
    }

    /**
     * Customer Company Nameプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetCustomerCompanyNameList()
    {

        $query = MstCustomer::query();

        // プルダウンリストを取得する
        return $query->select('customer_id as id', 'customer_company_name as value')
            ->orderby('customer_cd')
            ->get()
            ->keyBy('id');
    }

    /**
     * Biological Cell Classificationプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetBiologicalList()
    {

        $query = MstBioCellClass::query();

        // プルダウンリストを取得する
        return $query->select('bio_cell_class_id as id', 'bio_cell_class_name as value')
            ->orderby('display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Micro-organisms speciesプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetMicroOrgSpeciesList($bioCellClassId = null)
    {
        $query = MstMicroOrgSpecies::query();

        // bio_cell_class_idが指定された場合絞り込み
        $query->when($bioCellClassId, function ($query) use ($bioCellClassId) {
            return $query->where('bio_cell_class_id', $bioCellClassId);
        });

        // プルダウンリストを取得する
        return $query->select('micro_org_species_id as id', 'micro_org_species_name as value')
            ->orderby('display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Biological Cell Classificationの表示順付きプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetBiologAndDispOrderList()
    {
        $query = MstBioCellClass::query();

        // プルダウンリストを取得する
        return $query->select('mst_bio_cell_classes.bio_cell_class_id as id', DB::raw("CONCAT(mst_bio_cell_classes.display_order, ' (', mst_bio_cell_classes.bio_cell_class_name, ')') as value"))
            ->orderby('mst_bio_cell_classes.display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Morphologyプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetMorphologyList()
    {

        $query = MstMorphology::query();

        // プルダウンリストを取得する
        return $query->select('morphology_id as id', 'morphology_name as value')
            ->orderby('display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Polyploidプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetPolyploidList()
    {

        $query = MstPolyploid::query();

        // プルダウンリストを取得する
        return $query->select('polyploid_id as id', 'polyploid_name as value')
            ->orderby('display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Container Nameプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetContainerList()
    {

        $query = MstSampleContainer::query();

        // プルダウンリストを取得する
        return $query->select('mst_sample_containers.container_id as id', 'mst_sample_containers.container_name', 'mst_codes.name as container_type')
            // コードマスターとJOIN（Container Type Code）
            ->sdLeftJoin(CodeMaster::class, function ($join) {
                $join->on('mst_sample_containers.container_type_cd', '=', 'mst_codes.code')
                    ->where('data_type', AppConst::CODE_MASTER_4);
            })
            ->orderby('display_order')
            ->get()
            ->keyBy('id');
    }

    /**
     * Irradiation Operatorプルダウンメニューのリストを取得
     * @param bool $en
     * @return array
     */
    protected function mdlGetOperatorList($en = true)
    {

        $query = MstIrrOperator::query();

        // Company MasterとJOIN
        $query->sdLeftJoin(MstCompany::class, 'mst_companies.company_id', '=', 'mst_irr_operators.company_id')
            // コードマスタとJOIN（Company Type Code）
            ->sdLeftJoin(CodeMaster::class, function ($join) {
                $join->on('mst_companies.company_type_cd', '=', 'mst_codes.code')
                    ->where('mst_codes.data_type', AppConst::CODE_MASTER_2);
            });

        // 言語に応じたプルダウンリストを取得する
        // MEMO: 「name : (type) company」の形式
        $query->when($en == true, function ($query) {
            return $query->select(
                'mst_irr_operators.irradiation_operator_id as id',
                DB::raw("CONCAT(mst_irr_operators.irradiation_operator_en, ' : (', mst_codes.name, ') ', mst_companies.company_name) as value")
            )
                ->orderby('mst_irr_operators.irradiation_operator_en', 'asc');
        });

        $query->when($en == false, function ($query) {
            return $query->select(
                'mst_irr_operators.irradiation_operator_id as id',
                DB::raw("CONCAT(mst_irr_operators.irradiation_operator_jp, ' : (', mst_codes.name, ') ', mst_companies.company_name) as value")
            )
                ->orderby('mst_irr_operators.irradiation_operator_jp', 'asc');
        });

        return $query
            ->orderby('mst_companies.company_type_cd', 'asc')
            ->orderby('mst_companies.company_name', 'asc')
            ->orderby('mst_irr_operators.irradiation_operator_id', 'asc')
            ->get()
            ->keyBy('id');
    }

    /**
     * Company Type、Company Name プルダウンメニューのリストを取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function mdlGetCompanyNameList()
    {
        $query = MstCompany::query();

        // プルダウンリストを取得する
        return $query
            ->select('company_id as id', DB::raw("CONCAT('(', mst_codes.name, ') ', company_name) as value"))
            ->sdLeftJoin(CodeMaster::class, function ($join) {
                $join->on('mst_companies.company_type_cd', '=', 'mst_codes.code')
                    ->where('mst_codes.data_type', AppConst::CODE_MASTER_2);
            })
            ->orderBy('mst_companies.company_type_cd', 'asc')
            ->orderBy('mst_companies.company_name', 'asc')
            ->orderBy('mst_companies.company_id', 'asc')
            ->get()
            ->keyBy('id');
    }

    /**
     * Beam Line Name プルダウンメニューのリストを取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function mdlGetBeamLineNameList()
    {
        $query = MstIrrBeamLine::query();

        // プルダウンリストを取得する
        return $query->select('beam_line_id as id', 'beam_line_name as value')
            ->orderBy('id', 'asc')
            ->get()
            ->keyBy('id');
    }

    /**
     * User Name プルダウンメニューのリストを取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function mdlGetUserNameList()
    {
        $query = User::query();

        // プルダウンリストを取得する
        return $query->select('user_id as id', 'user_name as value')
            ->orderBy('id', 'asc')
            ->get()
            ->keyBy('id');
    }

    /**
     * Equipment Name プルダウンメニューのリストを取得
     *
     * @param int $beamLineId Beam Line ID 省略可
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function mdlGetEquipmentNameList($beamLineId = null)
    {
        $query = MstIrrEquipment::query();

        // beam_line_idが指定された場合絞り込み
        $query->when($beamLineId, function ($query) use ($beamLineId) {
            return $query->where('beam_line_id', $beamLineId);
        });

        // プルダウンリストを取得する
        return $query->select('equipment_id as id', 'equipment_name as value')
            ->orderby('beam_line_id')
            ->orderby('equipment_id')
            ->get()
            ->keyBy('id');
    }

    /**
     * Equipment Name プルダウンメニューのリストを取得(Sample Setting Equipmentのみ)
     *
     * @param int $beamLineId Beam Line ID 省略可
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function mdlGetSampleEquipmentNameList($beamLineId = null)
    {
        $query = MstIrrEquipment::query();

        // beam_line_idが指定された場合絞り込み
        $query->when($beamLineId, function ($query) use ($beamLineId) {
            return $query->where('beam_line_id', $beamLineId);
        });

        // プルダウンリストを取得する
        return $query->select('equipment_id as id', 'equipment_name as value')
            ->where('equipment_type_cd', AppConst::CODE_MASTER_3_1)
            ->orderby('beam_line_id')
            ->orderby('equipment_id')
            ->get()
            ->keyBy('id');
    }

    /**
     * Alert RecipientのユーザーID付きプルダウンメニューのリストを取得
     *
     * @return array
     */
    protected function mdlGetAlertUserList()
    {
        $query = User::query();

        // プルダウンリストを取得する
        return $query->select('user_id as id', DB::raw("CONCAT(user_id, ' : ', user_name ) as value"))
            ->orderby('user_id')
            ->get()
            ->keyBy('id');
    }

    /**
     * RoleTypeのプルダウンリスト作成
     *
     * @param null
     */
    protected function mdlGetRoleTypeList()
    {
        $query = RoleType::query();

        // User Roleリスト取得
        return $query->select(
            'role_type_id',
            'role_type_name as value'
        )
            ->orderby('role_type_id')
            ->get()
            ->keyBy('role_type_id');
    }

    /**
     * UserRoleのプルダウンリスト作成
     *
     * @param null
     */
    protected function mdlGetUserRoleList()
    {
        $query = UserRole::query();

        // User Roleリスト取得
        return $query->select(
            'user_role_id',
            'User_role_name as value'
        )
            ->orderBy('role_type_id')
            ->orderBy('user_role_name')
            ->orderBy('user_role_id')
            ->get()
            ->keyBy('user_role_id');
    }

    /**
     * Equipment Areaのプルダウンリストを取得
     *
     * @return array
     */
    protected function mdlGetIrrEquipmentAreaList($equipment_id, $equipment_area_type_cd)
    {
        $query = MstIrrEquipmentArea::query();

        // Irradiation Equipment Areaリスト取得
        return $query->select(
            'equipment_id',
            'equipment_area_type_code',
            'area_number',
            'area_number_name as value'
        )
            ->where('equipment_id', $equipment_id)
            ->where('equipment_area_type_code', $equipment_area_type_cd)
            ->orderby('area_number')
            ->get()
            ->keyBy('area_number');
    }

    /**
     * 登録画面プルダウン用データフォーマット
     * name を 「コード (名称)」 の形式にする
     *
     * @param  $collection リストデータ
     * @param  int $digit コード0埋め桁数
     * @return フォーマット後リストデータ
     */
    protected function mdlFormatInputList($collection, int $digit)
    {
        $lists = $collection->map(function ($item, $key) use ($digit) {
            return [
                'code' => $item['code'],
                'value' => str_pad($item['code'], $digit, '0', STR_PAD_LEFT) . ' (' . $item['value'] . ')'
            ];
        });

        return $lists;
    }

    /**
     * Container Nameプルダウン用データフォーマット
     * name を 「name : type」 の形式にする
     *
     * @param  $collection リストデータ
     * @return フォーマット後リストデータ
     */
    protected function mdlFormatContainerList($collection)
    {
        $lists = $collection->map(function ($item, $key) {
            return [
                'code' => $item['code'],
                'value' => $item['container_name'] . ' : ' . $item['container_type']
            ];
        });

        return $lists;
    }

    //------------------------------
    // 数値リスト取得（共通で使用されるもの）
    //------------------------------

    /**
     * 数値プルダウンリストの取得
     *
     * @param int $min 開始値
     * @param int $max 終了値
     * @return array
     */
    protected function mdlNumberList($min = 0, $max = 99)
    {
        $list = [];
        for ($i = $min; $i <= $max; $i++) {
            $list[$i] = [
                "value" => $i
            ];
        }

        return $list;
    }

    //------------------------------
    // 名称取得（共通で使用されるもの）
    //------------------------------

    /**
     * // コードマスタの名称取得
     *
     * @param integer data_type
     * @param integer code
     * @return string name
     */
    protected function mdlGetNameFromCodeMaster($data_type, $code)
    {
        // 名称を取得
        $query = CodeMaster::query();
        $data = $query->select("name")
            ->where('data_type', $data_type)
            ->where("code", $code)
            // 該当データがない場合はエラーを返す
            ->firstOrFail();

        return $data->name;
    }

    //------------------------------
    // メールアドレス取得（共通で使用されるもの）
    //------------------------------

    /**
     * ユーザ情報からメールアドレスの取得
     *
     * @param string $userId ユーザID
     * @return string メールアドレス
     */
    protected function mdlGetUserMail($userId)
    {
        $user = User::select('email')
            ->where('user_id', $userId)
            ->firstOrFail();

        return $user->email;
    }

    //------------------------------
    // join向けリストの作成
    //------------------------------


    //------------------------------
    // whereの条件
    //------------------------------


    //------------------------------
    // SQLヘルパー
    //------------------------------

    /**
     * テーブル項目の日付のフォーマット 年月日
     *
     * @param string $col カラム名
     */
    protected function mdlFormatYmd($col)
    {
        return DB::raw("DATE_FORMAT(" . $col . ", '%Y-%m-%d')");
    }
}
