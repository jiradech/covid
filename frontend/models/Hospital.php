<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hospital".
 *
 * @property string $hoscode
 * @property string|null $hosname
 * @property string|null $hostype
 * @property string|null $address
 * @property string|null $road
 * @property string|null $mu
 * @property string|null $subdistcode
 * @property string|null $distcode
 * @property string|null $provcode
 * @property string|null $postcode
 * @property string|null $hoscodenew
 * @property string|null $bed จำนวนเตียง
 * @property string|null $level_service ระดับการบริการ
 * @property string|null $bedhos
 * @property int|null $hdc_regist
 * @property string|null $dep
 * @property string|null $hmain_sent
 * @property string|null $register_date
 * @property string|null $mcode
 * @property float|null $status
 */
class Hospital extends \yii\db\ActiveRecord
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
        return 'hospital';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hoscode'], 'required'],
            [['hdc_regist'], 'integer'],
            [['register_date'], 'safe'],
            [['status'], 'number'],
            [['hoscode', 'postcode', 'bed', 'bedhos', 'dep', 'hmain_sent', 'mcode'], 'string', 'max' => 5],
            [['hosname', 'level_service'], 'string', 'max' => 255],
            [['hostype', 'mu', 'subdistcode', 'distcode', 'provcode'], 'string', 'max' => 2],
            [['address', 'road'], 'string', 'max' => 50],
            [['hoscodenew'], 'string', 'max' => 9],
            [['hoscode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hoscode' => 'Hoscode',
            'hosname' => 'Hosname',
            'hostype' => 'Hostype',
            'address' => 'Address',
            'road' => 'Road',
            'mu' => 'Mu',
            'subdistcode' => 'Subdistcode',
            'distcode' => 'Distcode',
            'provcode' => 'Provcode',
            'postcode' => 'Postcode',
            'hoscodenew' => 'Hoscodenew',
            'bed' => 'Bed',
            'level_service' => 'Level Service',
            'bedhos' => 'Bedhos',
            'hdc_regist' => 'Hdc Regist',
            'dep' => 'Dep',
            'hmain_sent' => 'Hmain Sent',
            'register_date' => 'Register Date',
            'mcode' => 'Mcode',
            'status' => 'Status',
        ];
    }
}
