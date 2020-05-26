<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "geo_village".
 *
 * @property string $villagecodefull
 * @property string $villagecode
 * @property string $villagename
 * @property string $tambonname
 * @property string $tamboncode
 * @property string $ampurcode
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $subdistrict
 * @property string $district
 * @property string $province
 * @property string $display
 * @property string $visibility
 * @property string $changwatcode
 * @property string $coordinates
 */
class GeoVillage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_village';
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
            //[['villagecodefull', 'tamboncode', 'ampurcode','villagecode'], 'required'],
            [['tamboncode', 'ampurcode','villagecode','villagename','changwatcode'], 'required'],
            [['id'], 'integer'],
            [['villagecodefull'], 'string', 'max' => 8],
            [['villagename', 'tambonname', 'name', 'description', 'display', 'visibility', 'coordinates'], 'string', 'max' => 255],
            [['tamboncode'], 'string', 'max' => 6],
            [['ampurcode'], 'string', 'max' => 4],
            [['subdistrict', 'district', 'province'], 'string', 'max' => 80],
            [['changwatcode','villagecode'], 'string', 'max' => 2],
            [['villagecodefull'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'villagecodefull' => 'Villagecodefull',
            'villagecode'=>'หมู่ที่',
            'villagename' => 'ชื่อหมู่บ้าน',
            'tambonname' => 'Tambonname',
            'tamboncode' => 'ตำบล',
            'ampurcode' => 'อำเภอ',
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'subdistrict' => 'Subdistrict',
            'district' => 'District',
            'province' => 'Province',
            'display' => 'Display',
            'visibility' => 'Visibility',
            'changwatcode' => 'จังหวัด',
            'coordinates' => 'พิกัด',
        ];
    }
}
