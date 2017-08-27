<?php


namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class OrderController extends Controller
{
    public function actionOrder()
    {
        if (\Yii::$app->user->isGuest){
            echo '你还未登录~<a href="http://shop4.bigphp.cn/user/login">点击跳转登录</a>';
        }else{
        //获取用户地址信息
        $address = Address::findAll(['member_id'=>\Yii::$app->user->id]);
        //获取该用户购物车的所有商品id
        $ids = Cart::find()->select('goods_id')->where(['member_id'=>\Yii::$app->user->id])->column();
        //获取该用户的所有商品信息
        $goods = Goods::find()->andWhere(['in','id',$ids])->all();
        //获取该用户的所有购物车信息
        $cartone =Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //判定没有购物车的情况
        if ($cartone) {
            //遍历购物车信息
            foreach ($cartone as $k =>$v)
            {
                $carts[$v->goods_id] = $v->amount;
            }
        }else{
            $carts =[];
        }

        //判定接收方式
        if (\Yii::$app->request->isPost){
        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        //异常捕获
        try{
            $data = \Yii::$app->request->post();
            //实例订单对象
            $order = new Order();
            //根据传过来的地址id获取地址信息
            $address = Address::findOne(['id'=>$data['address_id']]);
            //绑定数据
            $order->province = $address->cmbProvince;
            $order->city = $address->cmbCity;
            $order->area = $address->cmbArea;
            $order->address = $address->address;
            $order->tel = $address->tel;
            $order->name = $address->name;
            //获取订单的配送方式并绑定
            $order->delivery_id = \Yii::$app->request->post('delivery_id');
            $order->delivery_name = Order::$deliverys[$order->delivery_id][0];
            $order->delivery_price = Order::$deliverys[$order->delivery_id][1];
            //获取支付方式并绑定
            $order->payment_id = \Yii::$app->request->post('pay_id');
            $order->payment_name = Order::$pays[$order->payment_id][0];
            $order->total  = \Yii::$app->request->post('total');
            $order->status  = 1;
            $order->create_time  = time();
            $order->member_id  = \Yii::$app->user->id;
            if ($order->validate()){
                $order->save();
            }else{

                var_dump($order->getErrors());echo 22;exit;
            }
            //依次查询购物车商品的库存
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            //
            foreach ($carts as $cart){
                //查询单条商品信息
                $goods = Goods::findOne(['id'=>$cart->goods_id]);
                //判定库存
                if ($cart->amount > $goods->stock){
                    //抛出异常
                    throw new Exception('库存不足~请重新下单');
                }
                //库存足够保存数据到订单详情表
                $order_goods = new OrderGoods();
                $order_goods->order_id = $order->id;
                $order_goods->goods_id = $goods->id;
                $order_goods->goods_name = $goods->name;
                $order_goods->logo = $goods->logo;
                $order_goods->price = $goods->shop_price;
                $order_goods->amount = $cart->amount;
                $order_goods->total = $goods->shop_price;
                if ($order_goods->validate()){
                    $order_goods->save();
                }else{
                    var_dump($order_goods->getErrors());echo 22;exit;
                }
                //修改商品数量
                Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);

            }
             //提交订单
             $transaction->commit();
            Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
            return $this->redirect(['list']);

        }catch (Exception $e){
            //捕获异常回滚事务
            $transaction->rollBack();
            echo  $e->getMessage().'<a href="http://shop4.bigphp.cn/order/order">返回修改</a>';
            exit;

        }
       }


        return $this->render('order',['address'=>$address,'goods'=>$goods,'carts'=>$carts]);
        }
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }


    public function actionList()
    {
        if (\Yii::$app->user->isGuest) {
            echo '你还未登录~<a href="http://shop4.bigphp.cn/user/login">点击跳转登录</a>';
        } else {
            //获取用户id
            $member_id = \Yii::$app->user->id;
            //根据用户id连表查询订单表和订单详情表
            $orders = Order::find()
                ->select('order_goods.logo,order.name,order.status,order.total,order.delivery_name,order.tel,order.create_time,order_goods.order_id')
                ->from('order')
                ->join('INNER JOIN', 'order_goods', 'order.id=order_goods.order_id')
                ->where(['order.member_id' => $member_id])
                ->asArray()
                ->all();
            return $this->render('list', ['orders' => $orders,]);
        }
    }

}