<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pui_lab".
 *
 * @property int $id
 * @property string $pui_code
 * @property string $referal_no
 * @property string|null $sample_place
 * @property string|null $sample_type
 * @property string|null $pcr_send_date
 * @property string|null $pcr_result
 * @property string|null $pcr_date
 * @property string|null $pcr_time
 * @property string|null $note
 */
class PuiLab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
 public static function getDb()
    {
        return Yii::$app->get('db_covid');
    }   
    public static function tableName()
    {
        return 'pui_lab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pui_code'], 'required'],
            [['pcr_send_date', 'pcr_date', 'pcr_time'], 'safe'],
            [['note'], 'string'],
            [['pui_code'], 'string', 'max' => 20],
            [['referal_no'], 'string', 'max' => 5],
            [['sample_place', 'sample_type', 'pcr_result'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pui_code' => 'Pui Code',
            'referal_no' => 'Referal No',
            'sample_place' => 'สถานที่ส่งตรวจตัวอย่าง',
            'sample_type' => 'ชนิดตัวอย่าง',
            'pcr_send_date' => 'วันที่ส่งตรวจ',
            'pcr_result' => 'ผล PCR',
            'pcr_date' => 'วันที่ผล PCR',
            'pcr_time' => 'เวลา',
            'note' => 'บันทึกเพิ่มเติม',
        ];
    }
}
