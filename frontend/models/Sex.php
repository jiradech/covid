<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sex".
 *
 * @property string $sex
 * @property string|null $sexname
 */
class Sex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sex';
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
            [['sex'], 'required'],
            [['sex'], 'string', 'max' => 1],
            [['sexname'], 'string', 'max' => 4],
            [['sex'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sex' => 'Sex',
            'sexname' => 'Sexname',
        ];
    }
}
