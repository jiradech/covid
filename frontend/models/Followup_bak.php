<?php

namespace app\models;

use Yii;

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
class Followup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'followup';
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
            [['temp'], 'number'],
            [['note'], 'string'],
            [['cid'], 'string', 'max' => 13],
            [['q_fever', 'q_sick_sign'], 'string', 'max' => 1],
            [['sick_sign', 'remark'], 'string', 'max' => 200],
            [['reporter_name'], 'string', 'max' => 120],
            [['reporter_phone'], 'string', 'max' => 20],
            [['report_date','remark','cid'], 'required'],
            
        ];
    }

    public function notify_message($message,$token)
    {
       $line_api = 'https://notify-api.line.me/api/notify';
       $line_token = $token;
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
        if ($this->temp >= 37.5) {
            $this->q_fever = '1';
        } else {
            $this->q_fever = '0';
        }
        return parent::beforeSave($insert);
    }



    // public function afterSave($insert) 
    // {
    //     if ($this->temp >= 37.5) {
    //         $this->notify_message('เฝ้าระวังพบบุคคลมีไข้ '.'http://www.sko.moph.go.th/covid19/index.php?r=person/view&id='.$this->id,
    //             'rxfne7Q6EI1YG4pwH290PuFTwsT18X43PXE0wOITvAg');
    //     }
    //     return parent::afterSave($insert);
    // }
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
            'remark' => 'สถานะการจำหน่าย',
            'note' => 'บันทึกเพิ่มเติม',
            'reporter_name' => 'ผู้รายงาน',
            'reporter_phone' => 'เบอร์โทรศัพท์ผู้รายงาน',
            'last_update' => 'วันที่รายงาน',
        ];
    }
}
