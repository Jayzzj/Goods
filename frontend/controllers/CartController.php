<?php


namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\ViewAction;

class CartController extends Controller
{
    //商品提示页
    public function actionNotice($goods_id, $amount)
    {
        //判定是否登录
        if (\Yii::$app->user->isGuest) {
            //未登录数据存在cookie中
            //实例cookie对象
            $cookier = \Yii::$app->request->cookies;
            //获取cookie中的购物车
            $carts = $cookier->getValue('carts');
            //判定当前购物车是否存在
            if ($carts) {
                //存在序列化carts购物车数据
                $carts = unserialize($carts);
                //用当前goods_id判定当前商品是否存在
                if (array_key_exists($goods_id, $carts)) {
                    //存在只修改数量
                    $carts[$goods_id] += $amount;
                } else {
                    //不存在新增添加商品到购物车
                    $carts[$goods_id] = $amount;
                }
            } else {
                //创建一个新的购物车
                $carts = [];
                $carts[$goods_id] = $amount;
            }
            //将数据保存到cookie中
            //实例保存cookie对象
            $cookiep = \Yii::$app->response->cookies;
            $cookiep->add(new Cookie([
                'name' => 'carts',
                'value' => serialize($carts),
                'expire' => time() + 30 * 24 * 3600//过期时间
            ]));

        } else {

            //登录情况下保存数据库
            $model = new Cart();
            //获取当前用户id
            $member_id = \Yii::$app->user->identity->id;
            //根据商品id去查询购物车表是否存在该数据
            $carts = Cart::find()->where(['goods_id' => $goods_id])->andWhere(['member_id' => $member_id])->one();

            if ($carts === null) {
                //不存在添加一条购物车信息
                $model->goods_id = $goods_id;
                $model->member_id = $member_id;
                $model->amount = $amount;
                $model->save();
            } else {
                //存在该商品信息只添加数量
                $carts->amount += $amount;
                $carts->save();
            }
        }

        return $this->redirect(['cart']);
    }

    public function actionCart()
    {
        //1.判定是否是登录状态
        if (\Yii::$app->user->isGuest) {
            //未登录状态重cookie中获取信息
            $cookies = \Yii::$app->request->cookies;

            //反序例化cookies中的数据
            $carts = unserialize($cookies->getValue('carts'));
//           var_dump($carts);exit;
            //取键名得到商品id数组
            $goods_ids = array_keys($carts);
            //根据ids数组获取所有商品信息
            $goods = Goods::find()->andWhere(['in', 'id', $goods_ids])->all();

        } else {
            //登录情况直接从数据库读取商品信息
            //根据当前用户信息获取购物车的所有商品id
            $id = \Yii::$app->user->identity->id;

            $goods_ids = Cart::find()->select('goods_id')->where(['member_id' => $id])->column();

            //根据所有的商品id获取当前用户的所有商品信息
            $goods = Goods::find()->andWhere(['in', 'id', $goods_ids])->all();
            //获取当前用户id的商品对应数量
            $carts1 = Cart::find()->andWhere(['member_id' => $id])->all();
            //遍历商品根据以商品id作为键商品数量作为值返回新数组
            foreach ($carts1 as $k => $v) {
                $carts[$v->goods_id] = $v->amount;
            }
//            var_dump($carts);exit;
            //$carts ='';
        }

        if(empty($carts)){
            echo '购物车没有商品~请添加商品'.'<a href="http://shop4.bigphp.cn/index/index">点击跳转</a>';exit;
        }

        //分配到视图
        return $this->render('cart', ['goods' => $goods, 'carts' => $carts]);

    }

    //关闭
    public $enableCsrfValidation = false;

    public function actionAjaxamount()
    {
        //接收ajax参数
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //判定是否登录
        if (\Yii::$app->user->isGuest) {
            //未登录改变cookie中的数据
            //实例cookie的对象
            $cookies = \Yii::$app->request->cookies;
            //获取当前购物车
            $carts = $cookies->getValue('carts');
            //反序列化序例化的购物车数据
            $carts = unserialize($carts);
            //根据判定购物车是否存在
            if ($carts) {
                //判定当前商品是否存在
                if (array_key_exists($goods_id, $carts)) {
                    //存在修改数量
                    $carts[$goods_id] = $amount;
                    //实例cookie保存对象
                    $cookier = \Yii::$app->response->cookies;
                    //保存数据
                    $cookier->add(new Cookie([
                        'name' => 'carts',//键
                        'value' => serialize($carts),//序列化保存的值
                        'expire' => time() + 30 * 24 * 3600//过期时间
                    ]));
                    echo 'success';
                }
            } else {
                echo '商品不存在,请刷新页面';
            }
        } else {
            //登录情况下
            //获取购物车对象
            $carts = Cart::findOne($goods_id);
            $carts->amount = $amount;
            $carts->save();
            echo 'success';
        }
    }

    public function actionAjaxdel()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        //判定登录情况
        if (\Yii::$app->user->isGuest) {
            $carts = \Yii::$app->request->cookies->getValue('carts');
            $carts = unserialize($carts);
            //未登录情况删除cookie中的值'
            if (array_key_exists($goods_id, $carts)) {
                unset($carts[$goods_id]);
            } else {
                return '商品不存在~请刷新页面';
            }
            //实例cookies保存对象
            $cookies = \Yii::$app->response->cookies;
            $cookies->add(new Cookie([
                'name' => 'carts',
                'value' => serialize($carts),
                'expire' => time() + 30 * 24 * 3600//过期时间
            ]));
            echo 'success';
        } else {
            //获取商品对象删除数据
            //获取当前用户id
            $id = \Yii::$app->user->identity->id;
            $goods = Cart::deleteAll(['goods_id' => $goods_id, 'member_id' => $id]);
//
            if ($goods) {
                echo 'success';
            }
        }
    }

    public function actionJiesuan()
    {
        //根据当前用户信息获取购物车的所有商品id
        $id = \Yii::$app->user->identity->id;

        $goods_ids = Cart::find()->select('goods_id')->where(['member_id' => $id])->column();

        //根据所有的商品id获取当前用户的所有商品信息
        $goods = Goods::find()->andWhere(['in', 'id', $goods_ids])->all();
        //获取当前用户id的商品对应数量
        $carts1 = Cart::find()->andWhere(['member_id' => $id])->all();
        //遍历商品根据以商品id作为键商品数量作为值返回新数组
        foreach ($carts1 as $k => $v) {
            $carts[$v->goods_id] = $v->amount;
        }
//            var_dump($carts);exit;
        //$carts ='';


        //分配到视图
        return $this->render('cart', ['goods' => $goods, 'carts' => $carts]);


    }
}