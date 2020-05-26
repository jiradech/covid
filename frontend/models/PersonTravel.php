<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property string $cid เลขประจำตัวประชาชน/เลข Passport (กรณีต่างชาติ)
 * @property string $prename คำนำหน้า
 * @property string $fname ชื่อ
 * @property string $lname นามสกุล
 * @property int $age อายุ
 * @property string $sex เพศ
 * @property string $occupation อาชีพ
 * @property string|null $phone_number หมายเลขโทรศัพท์
 * @property string $date_in วันที่เข้าพื้นที่ (กรณีมาจากต่าง จ.)
 * @property string|null $move_province
 * @property string|null $move_ampur
 * @property string|null $move_tambon
 * @property string|null $move_vill_no
 * @property string|null $addr_number บ้านเลขที่
 * @property string|null $addr_vill_no หมู่ที่
 * @property string $addr_tambon ตำบล
 * @property string $addr_ampur อำเภอ
 * @property string $addr_province จังหวัด
 * @property string $nation สัญชาติ
 * @property string|null $house_type ประเภทที่อยู่อาศัย
 * @property int|null $c_family จำนวนสมาชิกในครัวเรือน
 * @property string|null $q_fever มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้
 * @property float|null $temp อุณหภูมิ
 * @property string|null $q_sick_sign มีอาการอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )
 * @property string|null $sick_sign อาการแสดง
 * @property string|null $q_from_risk_country มีประวัติเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน?
 * @property string|null $q_close_to_case มีประวัติอยู่ใกล้ชิดกับผู้ป่วยยืนยัน COVID-19 (ใกล้กว่า 1 เมตร นานเกิน 5 นาที) ในช่วง 14 วันก่อน หรือ ไปสนามมวยลุมพินี หรือ ผับที่มีการพบผู้ติดเชื้อ?
 * @property string|null $risk_from_risk_country ท่านเดินทางกลับจากประเทศ 
 * @property string|null $risk_korea_worker แรงงานกลับจากประเทศเกาหลีใต้
 * @property string|null $risk_cambodia_border เดินทางข้ามพรมแดนกัมพูชา
 * @property string|null $risk_from_bangkok เดินทางกลับมาจากกรุงเทพ
 * @property string|null $q_family_from_risk_country มีบุคคลในบ้านเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน?
 * @property string|null $q_close_to_foreigner ประกอบอาชีพใกล้ชิดกับชาวต่างชาติ?
 * @property string|null $q_healthcare_staff เป็นบุคลากรทางการแพทย์?
 * @property string|null $q_close_to_group_fever มีผู้ใกล้ชิดป่วยเป็นไข้หวัดพร้อมกัน มากกว่า 5 คน ในช่วง 14 วันก่อน?
 * @property string|null $risk_place เคยไปสถานที่เสี่ยงที่มีคนแออัดเบียดเสียด
 * @property string|null $risk_group_place เคยไปร่วมกิจกรรมที่มีคนรวมกลุ่มกันเป็นจำนวนมากๆ
 * @property string|null $risk_case_place ใกล้ชิดกับผู้ป่วยติดเชื้อหรือไปร่วมอยู่ในสถานที่ที่มีผู้ป่วยติดเชื้อ
 * @property string|null $note บันทึกเพิ่มเติม
 * @property string|null $reporter_name ชื่อผู้รายงาน
 * @property string|null $reporter_phone หมายเลขโทรศัพท์ผู้รายงาน
 * @property string|null $date_stamp
 * @property string|null $remark
 */
class PersonTravel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person_travel';
    }

    public function beforeSave($insert) 
    {
       $this->addr_province = '27';
        return parent::beforeSave($insert);
    }
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_covid');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['q_close_to_case', 'risk_korea_worker', 'risk_cambodia_border', 'risk_from_bangkok', 'q_family_from_risk_country', 'q_close_to_foreigner', 'q_healthcare_staff', 'q_close_to_group_fever', 'risk_place', 'risk_group_place','reporter_name', 'cid', 'fname', 'lname', 'age', 'sex', 'date_in','date_out', 'addr_vill_no', 'addr_tambon', 'addr_ampur', 'nation'], 'required'],
            [['age', 'c_family'], 'integer'],
            [['date_in','date_out', 'date_stamp'], 'safe'],
            [['temp'], 'number'],
            [['note'], 'string'],
            [['cid'], 'string', 'max' => 13],
            [['prename'], 'string', 'max' => 5],
            [['fname', 'lname', 'addr_number','move_number'], 'string', 'max' => 50],
            [['sex', 'move_ampur', 'addr_ampur'], 'string', 'max' => 4],
            [['occupation', 'phone_number', 'house_type', 'risk_from_risk_country', 'reporter_phone'], 'string', 'max' => 50],
            [['move_province', 'move_vill_no', 'addr_province','origin_province'], 'string', 'max' => 2],

            [['move_tambon'], 'string', 'max' => 6],

            [['addr_vill_no'], 'string', 'max' => 3],
            [['addr_tambon'], 'string', 'max' => 6],
            [['q_fever', 'q_sick_sign', 'q_from_risk_country', 'q_close_to_case', 'risk_korea_worker', 'risk_cambodia_border', 'risk_from_bangkok', 'q_family_from_risk_country', 'q_close_to_foreigner', 'q_healthcare_staff', 'q_close_to_group_fever', 'risk_place', 'risk_group_place'], 'string', 'max' => 1],
            [['sick_sign', 'nation'], 'string', 'max' => 200],
            [['risk_case_place','objective','remark'], 'string', 'max' => 255],
            [['reporter_name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'เลขประจำตัวประชาชน/เลขที่หนังสือเดินทาง',
            'prename' => 'คำนำหน้า',
            'fname' => 'ชื่อ',
            'lname' => 'นามสกุล',
            'age' => 'อายุ',
            'sex' => 'เพศ',
            'occupation' => 'อาชีพ',
            'phone_number' => 'หมายเลขโทรศัพท์',
            'date_in' => 'วันที่เดินทาง',
            'date_out'=>'วันที่ออก',
            'move_province' => 'จังหวัด',
            'move_ampur' => 'อำเภอ',
            'move_tambon' => 'ตำบล',
            'move_vill_no' => 'หมู่บ้าน',
            'move_number'=>'เลขที่',
            'addr_number' => 'เลขที่',
            'addr_vill_no' => 'หมู่ที่',
            'addr_tambon' => 'ตำบล',
            'addr_ampur' => 'อำเภอ',
            'addr_province' => 'จังหวัด',
            'origin_province'=>'จังหวัด',
            'nation' => 'สัญชาติ',
            'house_type' => 'ประเภทที่อยู่อาศัย',
            'c_family' => 'จำนวนสมาชิกในครัวเรือน',
            'q_fever' => 'มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้',
            'temp' => 'อุณหภูมิ',
            'q_sick_sign' => 'มีอาการอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )',
            'sick_sign' => 'อาการแสดง',
            'q_from_risk_country' => 'เคยเดินทางไปยัง หรือ มาจากต่างประเทศ ไม่ว่าจะผ่านช่องทางใดก็ตาม?',
            'q_close_to_case' => 'เคยสัมผัสใกล้ชิดกับผู้ป่วยยืนยันโรคติดเชื้อไวรัสโคโรนา 2019 เช่น  มีผู้ป่วยร่วมบ้าน      
ที่ทำงาน',
            'risk_from_risk_country' => 'ท่านเดินทางกลับจากประเทศ ',
            'risk_korea_worker' => 'xxx',
            'risk_cambodia_border' => 'xxx',
            'risk_from_bangkok' => 'xxx',
            'q_family_from_risk_country' => 'xxx?',
            'q_close_to_foreigner' => 'ประกอบอาชีพเกี่ยวข้องกับ นักท่องเที่ยว  สถานที่แออัด  หรือ ติดต่อกับคนจำนวนมาก ',
            'q_healthcare_staff' => 'xxx',
            'q_close_to_group_fever' => 'xxx',
            'risk_place' => 'นอกจากจังหวัดที่เดินทางมาจากตามข้อ 1 เคยเดินทางไปยัง หรือ มาจากจังหวัดใดบ้าง       ในรอบ 14 วัน ก่อนเข้าพักครั้งนี้ ',
            'risk_group_place' => 'เคยไปในสถานที่ชุมชน หรือสถานที่ที่มีการรวมกลุ่มคน เช่น ตลาดนัด ห้างสรรพสินค้า สถานพยาบาล หรือ ขนส่งสาธารณะ',
            'risk_case_place' => 'xxx',
            'note' => 'บันทึกเพิ่มเติม',
            'reporter_name' => 'ผู้รายงาน ',
            'reporter_phone' => 'หมายเลขโทรศัพท์ผู้รายงาน ',
            'objective'=>'เพื่อ',
            'date_stamp' => 'Date Stamp',
             'remark'=>'โรงแรม/กิจการให้เช่าที่พัก',
        ];
    }


    public static function  getSexArray($id)
    {
        return ArrayHelper::map(Sex::find()
            ->select(['sex','sexname'])
            ->where(['sex'=>$id])
            ->all(), 'sex', 'sexname');
    }
    public static function  getVillageArray($id)
    {
        return ArrayHelper::map(Village::find()
            ->select(['villagecodefull','concat(villagecode," ",villagename) as  villagename'])
            ->where(['villagecodefull'=>$id])
           // ->andwhere('villagecode <> 00')
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

    public static function  getNationArray($id)
    {
        return ArrayHelper::map(Nation::find()
            ->select(['nationcode','nationname'])
            ->where(['nationname'=>$id])
            ->all(), 'nationname', 'nationname');
    }

    public static function  getProvinceArray($id)
    {
        return ArrayHelper::map(Province::find()
            ->select(['changwatcode','changwatname'])
            ->where(['changwatcode'=>$id])
            //->andwhere('villagecode <> 00')
            ->all(), 'changwatcode', 'changwatname');
    }

    public static function  getTypeHomeArray($id)
    {
        return ArrayHelper::map(TypeHome::find()
            ->select(['id','type'])
            ->where(['id'=>$id])
            ->all(), 'id', 'type');
    }

    public static function  getCountryriskArray($id)
    {
        return ArrayHelper::map(Countryrisk::find()
            ->select(['countryid','countryname'])
            ->where(['countryid'=>$id])
            ->all(), 'countryid', 'countryname');
    }
}
