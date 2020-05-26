<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "countryrisk".
 *
 * @property string $countryid
 * @property string|null $countryname
 */
class Countryrisk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countryrisk';
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
            [['countryname'], 'string', 'max' => 255],
            [['riskgroup', 'epidemicgroup'], 'string', 'max' => 2],
           // [['countryid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'countryid' => 'Countryid',
            'countryname' => 'Countryname',
            'riskgroup' => 'Riskgroup',
            'epidemicgroup' => 'Epidemicgroup',
        ];
    }
}
