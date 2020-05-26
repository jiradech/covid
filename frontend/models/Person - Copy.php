<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use app\models\Countryrisk;
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
class Person extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    public function notify_message2($message,$token)
    {
       $line_api = 'https://notify-api.line.me/api/notify';
       $line_token = $token;
       // 'vhWpSdQoE53kAGmssUuFVFV4c1W2HYc2GoY8MzDGkf8' คปสจ.
       // PV18y6G9EnJJH5Rv4iU83sJ0c89BwplCKxTORBaRqbv  ไอทีสาสุข
       $queryData = array('message' => $message); 
       $queryData = http_build_query($queryData,'','&');
       $headerOptions = array( 'http'=>array(
           'method'=>'POST',
           'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
               ."Authorization: Bearer ".$line_token."\r\n"
               ."Content-Length: ".strlen($queryData)."\r\n",
           'content' => $queryData
           )
       );
       $context = stream_context_create($headerOptions);
       $result = file_get_contents($line_api, FALSE, $context);
       $res = json_decode($result);
       return $res;
   }


    public function beforeSave($insert) 
    {
       $this->addr_province = '27';

       if ($this->temp >= 37.5) {
        $this->q_fever = '1';
       } else {
        $this->q_fever = '0';
       }

       $country = Countryrisk::find()->where(['countryname' => $this->risk_from_risk_country])->one();

       if ($country ) {
        if ($country->riskgroup == '1' || $country->epidemicgroup == '1' ) {
            $this->q_from_risk_country = '1';
        } else {
            $this->q_from_risk_country = '0';
        }
       } else {
        $this->q_from_risk_country = '0';
       }


    //    Video::updateAll([
    //     'position' => new \yii\db\Expression('@a := @a + 1'),
    // ], $condition);


        return parent::beforeSave($insert);
    }



    public function afterSave($insert, $changedAttributes) 
    {
        
         $date = date("Y-m-d", strtotime($this->date_in));

     
        //  $now = date("H:i");
        //  $begin = date("6:00");
        //  $end = date("22:00");
        //  & ($now >= $begin && $now <= $end)

        if ($this->temp >= 37.5 & $date == date("Y-m-d")) {

            $person = Person::find()->where(['cid' => $this->cid])->one();
            if ($person) {
                $amphur = Ampur::find()->where(['ampurcodefull' => $person->addr_ampur])->one();
                if ($amphur ) {
                    $amphur = 'ใน อ.'.$amphur->ampurname;
                } else {
                    $amphur = " ";
                }
                $this->notify_message2('แจ้งการเฝ้าระวังพบบุคคลมีไข้'.$amphur.' ดูข้อมูลเพิ่มเติมได้ที่: http://www.sko.moph.go.th/covid19/index.php?r=person/view&id='.$person->id,
                    '7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');//กลุ่ม COVID19 7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV
            }
                    //กลุ่ม Hardcore mFOiOeNrlEAZc7R82JiWT71rtcyA6W4PC2K4gE2bZiC

        }
        parent::afterSave($insert, $changedAttributes);
    }



    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_covid');
    }

    public function validateDateInput($attribute, $params) {

        $submit_date = $date = date("Y-m-d", strtotime($this->$attribute));
        
        
        // date_sub($date, date_interval_create_from_date_string('12 years'));
        // $minAgeDate = date_format($date, 'Y-m-d');
        // date_sub($date, date_interval_create_from_date_string('100 years'));
        // $maxAgeDate = date_format($date, 'Y-m-d');
            if ($submit_date  > date("Y-m-d")) {
                $this->addError($attribute, 'คุณระบุวันที่ ที่ยังมาไม่ถึง');
            } elseif ($submit_date  < date("Y-m-d", strtotime('2020-02-01'))) {
                $this->addError($attribute, 'คุณระบุวันที่น้อนหลังมากเกินไป');
            }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['q_close_to_case', 'risk_korea_worker', 'risk_cambodia_border', 'risk_from_bangkok', 'q_family_from_risk_country', 'q_close_to_foreigner', 'q_healthcare_staff', 'q_close_to_group_fever', 'risk_place', 'risk_group_place','reporter_name', 'cid', 'fname', 'lname', 'age', 'sex', 'date_in', 'addr_vill_no', 'addr_tambon', 'addr_ampur', 'nation'], 'required'],
            [['c_family'], 'integer'],
            [['date_in', 'date_stamp'], 'safe'],
            [['note'], 'string'],
            [['cid'], 'string', 'max' => 13],
            [['prename'], 'string', 'max' => 5],
            [['fname', 'lname', 'addr_number'], 'string', 'max' => 50],
            [['sex', 'move_ampur', 'addr_ampur'], 'string', 'max' => 4],
            [['occupation', 'phone_number', 'house_type', 'reporter_phone'], 'string', 'max' => 20],
            [['move_province', 'move_vill_no', 'addr_province'], 'string', 'max' => 2],
            [['risk_from_risk_country'], 'string', 'max' => 50],
            [['move_tambon'], 'string', 'max' => 6],
            [['nation'], 'string', 'max' => 20],
            [['addr_vill_no'], 'string', 'max' => 3],
            [['addr_tambon'], 'string', 'max' => 6],
            [['q_fever', 'status', 'q_sick_sign', 'q_from_risk_country', 'q_close_to_case', 'risk_korea_worker', 'risk_cambodia_border', 'risk_from_bangkok', 'q_family_from_risk_country', 'q_close_to_foreigner', 'q_healthcare_staff', 'q_close_to_group_fever', 'risk_place', 'risk_group_place'], 'string', 'max' => 1],
            [['sick_sign'], 'string', 'max' => 200],
            [['risk_case_place','remark'], 'string', 'max' => 255],
            [['reporter_name'], 'string', 'max' => 80],

            // ['cid', 'unique','message'=>'พบเลขประจำตัวประชาชนนี้ มีข้อมูลอยู่ในระบบอยู่แล้ว!'],
            [['cid', 'date_in'], 'unique', 'targetAttribute' => ['cid', 'date_in'],'message'=>'พบบุคคลนี้ และวันที่เข้าพื้นที่นี้ มีข้อมูลอยู่ในระบบอยู่แล้ว!'],
            [['temp'], 'number','min'=>34,'max'=>42],
            [['age'], 'integer','min'=>0,'max'=>110],
            [['date_in'], 'validateDateInput']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'เลขประจำตัวประชาชน/เลข Passport (กรณีต่างชาติ)',
            'prename' => 'คำนำหน้า',
            'fname' => 'ชื่อ',
            'lname' => 'นามสกุล',
            'age' => 'อายุ',
            'sex' => 'เพศ',
            'occupation' => 'อาชีพ',
            'phone_number' => 'หมายเลขโทรศัพท์',
            'date_in' => 'วันที่เข้าพื้นที่',
            'move_province' => 'จังหวัด',
            'move_ampur' => 'อำเภอ',
            'move_tambon' => 'ตำบล',
            'move_vill_no' => 'หมู่บ้าน',
            'addr_number' => 'บ้านเลขที่',
            'addr_vill_no' => 'หมู่ที่',
            'addr_tambon' => 'ตำบล',
            'addr_ampur' => 'อำเภอ',
            'addr_province' => 'จังหวัด',
            'nation' => 'สัญชาติ',
            'house_type' => 'ประเภทที่อยู่อาศัย',
            'c_family' => 'จำนวนสมาชิกในครัวเรือน',
            'q_fever' => 'มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้',
            'temp' => 'อุณหภูมิ',
            'q_sick_sign' => 'มีอาการอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )',
            'sick_sign' => 'อาการแสดง',
            'q_from_risk_country' => 'มีประวัติเดินทางไป "ประเทศกลุ่มเสี่ยง" ตามประกาศกรมในช่วง 14 วันก่อน?',
            'q_close_to_case' => 'มีประวัติอยู่ใกล้ชิดกับผู้ป่วยยืนยัน COVID-19 (ใกล้กว่า 1 เมตร นานเกิน 5 นาที) ในช่วง 14 วันก่อน หรือ ไปสนามมวยลุมพินี หรือ ผับที่มีการพบผู้ติดเชื้อ?',
            'risk_from_risk_country' => 'มีประวัติเดินทางไปต่างประเทศในช่วง 14 วันก่อนหรือไม่? หากใช่ กรุณาระบุประเทศ. ',
            'risk_korea_worker' => 'แรงงานกลับจากประเทศเกาหลีใต้',
            'risk_cambodia_border' => 'เดินทางข้ามพรมแดนกัมพูชา',
            'risk_from_bangkok' => 'เดินทางกลับมาจากกรุงเทพ',
            'q_family_from_risk_country' => 'มีบุคคลในบ้านเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน?',
            'q_close_to_foreigner' => 'ประกอบอาชีพใกล้ชิดกับชาวต่างชาติ?',
            'q_healthcare_staff' => 'เป็นบุคลากรทางการแพทย์?',
            'q_close_to_group_fever' => 'มีผู้ใกล้ชิดป่วยเป็นไข้หวัดพร้อมกัน มากกว่า 5 คน ในช่วง 14 วันก่อน?',
            'risk_place' => 'เคยไปสถานที่เสี่ยงที่มีคนแออัดเบียดเสียด',
            'risk_group_place' => 'เคยไปร่วมกิจกรรมที่มีคนรวมกลุ่มกันเป็นจำนวนมากๆ',
            'risk_case_place' => 'ใกล้ชิดกับผู้ป่วยติดเชื้อหรือไปร่วมอยู่ในสถานที่ที่มีผู้ป่วยติดเชื้อ',
            'note' => 'บันทึกเพิ่มเติม',
            'reporter_name' => 'ชื่อผู้รายงาน',
            'reporter_phone' => 'หมายเลขโทรศัพท์ผู้รายงาน',
            'status' => 'สถานะการติดตาม',
            'date_stamp' => 'Date Stamp',
            'remark'=>'กรณีพักอยู่ในศูนย์กักตัว ระบุ',
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
