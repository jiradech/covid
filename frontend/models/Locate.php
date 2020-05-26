<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "locate".
 *
 * @property string $province
 * @property string $district
 * @property string|null $subdistrict
 * @property string|null $village
 */
class Locate extends \yii\db\ActiveRecord
{
    public $lost_days;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locate';
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
            [['province', 'district'], 'required'],
            [['province'], 'string', 'max' => 2],
            [['district'], 'string', 'max' => 4],
            [['subdistrict'], 'string', 'max' => 6],
            [['village'], 'string', 'max' => 8],
            [['lost_days'], 'number', 'max' => 14],
            [['province'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'province' => 'จังหวัด',
            'district' => 'อำเภอ',
            'subdistrict' => 'ตำบล',
            'village' => 'หมู่บ้าน',
        ];
    }
}
