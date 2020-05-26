<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "village".
 *
 * @property string $villagecode
 * @property string|null $villagename
 * @property string $villagecodefull
 * @property string $tamboncode
 * @property string $ampurcode
 * @property string $changwatcode
 * @property string|null $flag_status สถานนะของพื้นที่ 0=ปกติ 1=เลิกใช้(แยก/ย้ายไปพื้นที่อื่น)
 */
class Village extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'village';
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
            [['villagecode', 'villagecodefull', 'tamboncode', 'ampurcode', 'changwatcode'], 'required'],
            [['villagecode', 'changwatcode'], 'string', 'max' => 2],
            [['villagename'], 'string', 'max' => 255],
            [['villagecodefull'], 'string', 'max' => 8],
            [['tamboncode'], 'string', 'max' => 6],
            [['ampurcode'], 'string', 'max' => 4],
            [['flag_status'], 'string', 'max' => 1],
            [['villagecode', 'villagecodefull', 'tamboncode', 'ampurcode', 'changwatcode'], 'unique', 'targetAttribute' => ['villagecode', 'villagecodefull', 'tamboncode', 'ampurcode', 'changwatcode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'villagecode' => 'Villagecode',
            'villagename' => 'Villagename',
            'villagecodefull' => 'Villagecodefull',
            'tamboncode' => 'Tamboncode',
            'ampurcode' => 'Ampurcode',
            'changwatcode' => 'Changwatcode',
            'flag_status' => 'Flag Status',
        ];
    }
}
