<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ampur".
 *
 * @property string $ampurcode
 * @property string|null $ampurname
 * @property string $ampurcodefull
 * @property string $changwatcode
 * @property string|null $flag_status สถานนะของพื้นที่ 0=ปกติ 1=เลิกใช้(แยก/ย้ายไปพื้นที่อื่น)
 */
class Ampur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ampur';
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
            [['ampurcode', 'ampurcodefull', 'changwatcode'], 'required'],
            [['ampurcode', 'changwatcode'], 'string', 'max' => 2],
            [['ampurname'], 'string', 'max' => 255],
            [['ampurcodefull'], 'string', 'max' => 4],
            [['flag_status'], 'string', 'max' => 1],
            [['ampurcode', 'ampurcodefull', 'changwatcode'], 'unique', 'targetAttribute' => ['ampurcode', 'ampurcodefull', 'changwatcode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ampurcode' => 'Ampurcode',
            'ampurname' => 'Ampurname',
            'ampurcodefull' => 'Ampurcodefull',
            'changwatcode' => 'Changwatcode',
            'flag_status' => 'Flag Status',
        ];
    }
}
