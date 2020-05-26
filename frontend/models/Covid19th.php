<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "covid19th".
 *
 * @property int $id
 * @property int|null $confirmed
 * @property int|null $recovered
 * @property int|null $hospitalized
 * @property int|null $deaths
 * @property int|null $newConfirmed
 * @property int|null $newRecovered
 * @property int|null $newHospitalized
 * @property int|null $newDeaths
 * @property string|null $updateDate
 */
class Covid19th extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'covid19th';
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
            [['id'], 'required'],
            [['id', 'confirmed', 'recovered', 'hospitalized', 'deaths', 'newConfirmed', 'newRecovered', 'newHospitalized', 'newDeaths'], 'integer'],
            [['updateDate'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirmed' => 'Confirmed',
            'recovered' => 'Recovered',
            'hospitalized' => 'Hospitalized',
            'deaths' => 'Deaths',
            'newConfirmed' => 'New Confirmed',
            'newRecovered' => 'New Recovered',
            'newHospitalized' => 'New Hospitalized',
            'newDeaths' => 'New Deaths',
            'updateDate' => 'Update Date',
        ];
    }
}
