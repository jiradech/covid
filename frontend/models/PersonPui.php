<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "person_pui".
 *
 * @property int $id
 * @property string $pui_code
 * @property string $referal_no
 * @property string $pui_case
 * @property string|null $pui
 * @property string|null $pui_contact
 * @property string|null $full_name
 * @property string $cid
 * @property string|null $sex
 * @property int|null $age
 * @property string|null $nation
 * @property string|null $occupation
 * @property string|null $addr_no
 * @property string|null $addr_villno
 * @property string|null $addr_villname
 * @property string|null $addr_tambon
 * @property string|null $addr_amphur
 * @property string|null $addr_province
 * @property string|null $villcode
 * @property string|null $tamboncode
 * @property string|null $amphurcode
 * @property string|null $provincecode
 * @property string|null $sick_date
 * @property string|null $detect_date
 * @property string|null $report_date
 * @property string|null $report_time
 * @property string|null $reporter_name
 * @property string|null $reporter_phone
 * @property string|null $receiver_name
 * @property string|null $receiver_phone
 * @property string|null $admit_hosp
 * @property string|null $sample_place
 * @property string|null $sample_type
 * @property string|null $pcr_send_date
 * @property string|null $pcr_result
 * @property string|null $pcr_date
 * @property string|null $pcr_time
 * @property string|null $discharge_result
 * @property string|null $final_dx
 * @property string|null $discharge_date
 * @property string|null $follow_status
 * @property string|null $tracking_status
 */
class PersonPui extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    // สถานะการติดตาม
            const FS_1 = 'อยู่ระหว่างการเฝ้าระวัง';
            const FS_2 = 'อยู่ระหว่างการรักษา';
            const FS_3 = 'รักษาหาย';
            const FS_4 = 'เสียชีวิต';
            const FS_5 = 'สิ้นสุดการติดตาม';

    public static function getDb()
    {
        return Yii::$app->get('db_covid');
    }
    public static function tableName()
    {
        return 'person_pui';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pui_code', 'referal_no', 'pui_case', 'cid'], 'required'],
            [['age', 'sort_order'], 'integer'],
            [['sick_date', 'detect_date', 'report_date', 'end_date', 'report_time','pcr_send_date'], 'safe'],
            [['cid'], 'string', 'max' => 20],
            [['pui_code'], 'string', 'max' => 20],
            [['referal_no'], 'string', 'max' => 5],
            [['provincecode','pui_type'], 'string', 'max' => 2],
            [['pui', 'pui_contact'], 'string', 'max' => 1],
            [['full_name', 'reporter_name', 'receiver_name', 'discharge_date'], 'string', 'max' => 150],
            [['nation', 'occupation'], 'string', 'max' => 50],
            [['addr_no'], 'string', 'max' => 100],
            [['addr_villno'], 'string', 'max' => 3],
            [['pui_case','addr_villname', 'follow_status'], 'string', 'max' => 200],
            [['addr_tambon', 'addr_amphur', 'addr_province', 'pcr_date', 'pcr_time'], 'string', 'max' => 30],
            [['villcode'], 'string', 'max' => 8],
            [['tamboncode'], 'string', 'max' => 6],
            [['amphurcode', 'sex'], 'string', 'max' => 4],
            [['reporter_phone', 'receiver_phone'], 'string', 'max' => 15],
            [['admit_hosp', 'sample_place', 'sample_type', 'pcr_result', 'discharge_result', 'final_dx'], 'string', 'max' => 120],
            [['tracking_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pui_code' => 'PUI Code',
            'referal_no' => 'เลขที่หนังสือนำส่ง',
            'pui_case' => 'PUI Case',
            'pui_type' => 'ประเภท PUI',
            'pui' => 'Pui',
            'pui_contact' => 'PUI Contact',
            'full_name' => 'ชื่อ-นามสกุล',
            'cid' => 'เลขประจำตัวประชาชน',
            'sex' => 'เพศ',
            'age' => 'อายุ',
            'nation' => 'สัญชาติ',
            'occupation' => 'อาชีพ',
            'addr_no' => 'บ้านเลขที่',
            'addr_villno' => 'Addr Villno',
            'addr_villname' => 'Addr Villname',
            'addr_tambon' => 'Addr Tambon',
            'addr_amphur' => 'Addr Amphur',
            'addr_province' => 'Addr Province',
            'villcode' => 'หมู่',
            'tamboncode' => 'ตำบล',
            'amphurcode' => 'อำเภอ',
            'provincecode' => 'จังหวัด',
            'sick_date' => 'วันเริ่มป่วย',
            'detect_date' => 'วันเริ่มรักษา / วันพบผู้ป่วย',
            'report_date' => 'วันรับรายงาน',
            'report_time' => 'เวลารับรายงาน',
            'reporter_name' => 'ผู้แจ้ง',
            'reporter_phone' => 'เบอร์โทรผู้แจ้ง',
            'receiver_name' => 'ผู้รับแจ้ง',
            'receiver_phone' => 'เบอร์โทรผู้รับแจ้ง',
            'admit_hosp' => 'สถานที่รักษา (Admit ห้องแยกโรค)',
            'sample_place' => 'สถานที่ส่งตรวจตัวอย่าง',
            'sample_type' => 'ชนิดตัวอย่าง',
            'pcr_send_date'=>'วันที่ส่งตรวจ',
            'pcr_result' => 'ผล PCR',
            'pcr_date' => 'วันที่ผล PCR',
            'pcr_time' => 'เวลา',
            'discharge_result' => 'ผลการรักษา',
            'final_dx' => 'ผลการวินิจฉัยก่อนกลับบ้าน',
            'discharge_date' => 'วัน/เดือน/ปีที่จำหน่าย (D/C)',
            'follow_status' => 'สถานะการติดตาม',
            'tracking_status' => 'Tracking Status',
            'sort_order' => 'ลำดับที่',
        ];
    }
    public static function  getNationArray($id)
    {
        return ArrayHelper::map(Nation::find()
            ->select(['nationcode','nationname'])
            ->where(['nationname'=>$id])
            ->all(), 'nationname', 'nationname');
    }
    public static function  getVillageArray($id)
    {
        return ArrayHelper::map(Village::find()
            ->select(['villagecodefull','concat(villagecode," ",villagename) as  villagename'])
            ->where(['tamboncode'=>$id])
            ->andwhere('villagecode <> 00')
            ->all(), 'villagecodefull', 'villagename');
    }

    public static function  getTambonArray($id)
    {
        return ArrayHelper::map(Tambon::find()
            ->select(['tamboncodefull','tambonname'])
            ->where(['tamboncodefull'=>$id])
            //->andwhere('villagecode <> 00')
            ->all(), 'tamboncodefull', 'tambonname');
    }
    public static function  getAmphurArray($id)
    {
        return ArrayHelper::map(Ampur::find()
            ->select(['ampurcodefull','ampurname'])
            ->where(['ampurcodefull'=>$id])
            //->andwhere('villagecode <> 00')
            ->all(), 'ampurcodefull', 'ampurname');
    }
    public static function  getProvinceArray($id)
    {
        return ArrayHelper::map(Province::find()
            ->select(['changwatcode','changwatname'])
            ->where(['changwatcode'=>$id])
            //->andwhere('villagecode <> 00')
            ->all(), 'changwatcode', 'changwatname');
    }
    public static function  getHosArray($id)
    {
        return ArrayHelper::map(Hospital::find()
            ->select(['hoscode','concat(hoscode," ",hosname) as  hosname'])
            ->where(['provcode' => 27])
            ->andFilterWhere(['IN','hostype',[06,07,11,12]])
            //->orderBy('changwatname')
            //->groupBy(['admit_hosp'])
            ->all(),'hoscode','hosname');
    }
     public static function getFollowstatusArray()
    {
        return [
            self::FS_1 => 'อยู่ระหว่างการเฝ้าระวัง',
            self::FS_2 => 'อยู่ระหว่างการรักษา',
            self::FS_3 => 'รักษาหาย',
            self::FS_4 => 'เสียชีวิต',
            self::FS_5 => 'สิ้นสุดการติดตาม',
        ];
    }
}


