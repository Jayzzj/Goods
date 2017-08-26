<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property string $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{

    public static $deliverys =  [
        1=>['顺丰快递',20,'价格贵，速度快，服务好'],
        2=>['EMS',15,'价格贵，速度一般，服务一般'],
        3=>['邮政',0,'快递免费，速度一般，服务一般'],
    ];

    public static $pays = [
        1=>['在线支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        2=>['货到付款','送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        3=>['上门自提','自提时付款，支持现金、POS刷卡、支票支付'],

    ];
    //public $address_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['member_id', 'name', 'province', 'city', 'area', 'address', 'tel', 'delivery_id', 'delivery_name', 'delivery_price', 'payment_id', 'payment_name', 'total' ], 'required'],
            [['member_id', 'delivery_id', 'payment_id', 'status'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['create_time'], 'safe'],
            [['name', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'delivery_id' => 'Delivery ID',
            'delivery_name' => 'Delivery Name',
            'delivery_price' => 'Delivery Price',
            'payment_id' => 'Payment ID',
            'payment_name' => 'Payment Name',
            'total' => 'Total',
            'status' => 'Status',
            'trade_no' => 'Trade No',
            'create_time' => 'Create Time',
        ];
    }

    public function getOrdergoods()
    {
        return $this->hasOne(OrderGoods::className(),['order_id'=>'id']);

    }
}
