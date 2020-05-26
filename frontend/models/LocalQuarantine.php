<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "local_quarantine".
 *
 * @property int $id
 * @property string|null $local_name
 * @property string|null $addr_villno
 * @property string|null $addr_tambon
 * @property string|null $addr_amphur
 * @property string|null $addr_province
 * @property string|null $amphur
 * @property string|null $tambon
 * @property string|null $province
 * @property string|null $remark
 */
class LocalQuarantine extends \yii\db\ActiveRecord

{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'local_quarantine';
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
            [['local_name'], 'string', 'max' => 200],
            [['addr_villno', 'addr_province'], 'string', 'max' => 2],
            [['addr_tambon'], 'string', 'max' => 6],
            [['addr_amphur'], 'string', 'max' => 4],
            [['amphur', 'tambon', 'province'], 'string', 'max' => 40],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'local_name' => 'Local Name',
            'addr_villno' => 'Addr Villno',
            'addr_tambon' => 'Addr Tambon',
            'addr_amphur' => 'Addr Amphur',
            'addr_province' => 'Addr Province',
            'amphur' => 'Amphur',
            'tambon' => 'Tambon',
            'province' => 'Province',
            'remark' => 'Remark',
        ];
    }
}
