<?php

namespace app\models;

use Yii;
use app\models\PersonTravel;
use app\models\Ampur;
use app\models\FeverUpdate;
/**
 * This is the model class for table "followup".
 *
 * @property int $id
 * @property string|null $cid
 * @property string|null $report_date
 * @property string|null $q_fever มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้?
 * @property float|null $temp
 * @property string|null $q_sick_sign มีอาการอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )?
 * @property string|null $sick_sign
 * @property string|null $remark
 * @property string|null $note
 * @property string|null $reporter_name
 * @property string|null $reporter_phone
 * @property string|null $report_timestamp
 * @property string|null $last_update
 */
class FollowupTravel extends \yii\db\ActiveRecord
{
    public $person_id;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'followup_travel';
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
            [['report_date', 'report_timestamp', 'last_update'], 'safe'],
            [['temp', 'person_id'], 'number'],
            [['note'], 'string'],
            [['cid'], 'string', 'max' => 13],
            [['q_fever', 'q_sick_sign'], 'string', 'max' => 1],
            [['sick_sign', 'remark'], 'string', 'max' => 200],
            [['reporter_name'], 'string', 'max' => 120],
            [['reporter_phone'], 'string', 'max' => 20],
            [['report_date','cid'], 'required'],
             //[['report_date','remark','cid', 'reporter_name'], 'required'],
            [['temp'], 'number','min'=>34,'max'=>42],
            
        ];
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


    public function notify_message($message,$token)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'message='.$message);
        // follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token,
        ]);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);




    //    $line_api = 'https://notify-api.line.me/api/notify';
    //    $line_token = $token;
    //    $queryData = array('message' => $message); 
    //    $queryData = http_build_query($queryData,'','&');
    //    $headerOptions = array( 'http'=>array(
    //        'method'=>'POST',
    //        'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
    //            ."Authorization: Bearer ".$line_token."\r\n"
    //            ."Content-Length: ".strlen($queryData)."\r\n",
    //        'content' => $queryData
    //        )
    //    );
    //    $context = stream_context_create($headerOptions);
    //    $result = file_get_contents($line_api, FALSE, $context);
    //    $res = json_decode($result);
       return $server_output;
   }



    public function beforeSave($insert) 
    {
        if ($insert) {
            $person = PersonTravel::findOne($this->cid);
            $this->cid = $person->cid;

        } else {
            $person = PersonTravel::findOne($this->person_id);
        }


        if ($person != NULL) {
            
            
            $followup = FollowupTravel::find()->select('MAX(report_date) AS report_date')->where(['cid' => $this->cid])->one();

            if ($followup != NULL) {
                if (date($this->report_date) >= date($followup->report_date)) {
                    $person->status = $this->remark;
                    $person->save(false);
                }
            } else {
                $person->status = $this->remark;
                $person->save(false);
            }                
        }

        if ($this->temp >= 37.5) {
            $this->q_fever = '1';
        } else {
            $this->q_fever = '0';
        }




        return parent::beforeSave($insert);
    }



    public function afterSave($insert, $changedAttributes) 
    {
        
         $date = date("Y-m-d", strtotime($this->report_date));

     
        //  $now = date("H:i");
        //  $begin = date("6:00");
        //  $end = date("22:00");
        //  & ($now >= $begin && $now <= $end)
         
         
         
        if ($this->temp >= 37.5 & $date == date("Y-m-d")) {

            $person = PersonTravel::find()->where(['cid' => $this->cid])->one();
            if ($person) {
                $amphur = Ampur::find()->where(['ampurcodefull' => $person->addr_ampur])->one();
                if ($amphur ) {
                    $amphur = 'ใน อ.'.$amphur->ampurname;
                } else {
                    $amphur = " ";
                }
                // $this->notify_message2('แจ้งการเฝ้าระวังพบบุคคลมีไข้'.$amphur.' ดูข้อมูลเพิ่มเติมได้ที่: http://www.sko.moph.go.th/covid19/index.php?r=person-travel/view&id='.$person->id,
                //     '7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');

                    //กลุ่ม COVID19 7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV
            }
                    //กลุ่ม Hardcore mFOiOeNrlEAZc7R82JiWT71rtcyA6W4PC2K4gE2bZiC

        }
        parent::afterSave($insert, $changedAttributes);
    }

    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'temp' => 'อุณหภูมิ',
            'sick_sign' => 'อาการแสดง',
            'report_timestamp' => 'Report Timestamp',
            'id' => 'ID',
            'cid' => 'เลขประจำตัวประชาชน',
            'report_date' => 'วันที่ติดตาม',
            'q_fever' => 'มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้?',
            'q_sick_sign' => 'มีอาการอย่างใดอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )?',
            'remark' => 'สถานะการติดตาม',
            'note' => 'บันทึกเพิ่มเติม',
            'reporter_name' => 'ผู้รายงาน',
            'reporter_phone' => 'เบอร์โทรศัพท์ผู้รายงาน',
            'last_update' => 'วันที่รายงาน',
        ];
    }
}
