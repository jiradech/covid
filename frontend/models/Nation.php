<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nation".
 *
 * @property string $nationcode
 * @property string|null $nationname
 * @property string|null $nationcodeaec
 * @property string|null $nationrisk
 */
class Nation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nation';
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
            [['nationcode'], 'required'],
            [['nationcode', 'nationcodeaec', 'nationrisk'], 'string', 'max' => 4],
            [['nationname'], 'string', 'max' => 255],
            [['nationcode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nationcode' => 'Nationcode',
            'nationname' => 'Nationname',
            'nationcodeaec' => 'Nationcodeaec',
            'nationrisk' => 'Nationrisk',
        ];
    }
}
