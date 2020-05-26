<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property string $changwatcode
 * @property string|null $changwatname
 * @property string|null $zonecode
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'province';
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
            [['changwatcode'], 'required'],
            [['changwatcode', 'zonecode'], 'string', 'max' => 2],
            [['changwatname'], 'string', 'max' => 255],
            [['changwatcode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'changwatcode' => 'Changwatcode',
            'changwatname' => 'Changwatname',
            'zonecode' => 'Zonecode',
        ];
    }
}
