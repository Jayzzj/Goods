<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $address
 * @property integer $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel','cmbProvince', 'cmbCity', 'cmbArea','address'],'required'],
            [[ 'status'], 'integer'],
            [['name', 'cmbProvince', 'cmbCity', 'cmbArea'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cmbProvince' => 'Cmb Province',
            'cmbCity' => 'Cmb City',
            'cmbArea' => 'Cmb Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'status' => 'Status',
        ];
    }
}
