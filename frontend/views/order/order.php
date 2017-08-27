<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[
                    <?php if (isset(\Yii::$app->user->identity->username)){
                        echo \Yii::$app->user->identity->username;
                        $url = \yii\helpers\Url::to(['user/logout']);
                        echo  "[<a href=$url/>注销</a>]";
                    }else{
                        $url =\yii\helpers\Url::to(['user/login']);
                        echo "<a href=$url/>登录</a>]";
                        $url2 = \yii\helpers\Url::to(['user/register']);
                        echo "[<a href=$url2/>免费注册</a>]";
                    }

                    ?>
                </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <form action="" method="post">
        <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken?>">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php foreach ($address as  $v):?>
                    <input type="radio"
                           <?php if ($v->status ==1){
                               echo 'checked="checked"';
                        }?>
                           value="<?=$v->id?>" name="address_id"/><?=$v->name?>  <?=$v->tel?>  <?=$v->cmbProvince?> <?=$v->cmbCity?> <?=$v->cmbArea?><br/>
                    <?php endforeach;?>
               </p>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$deliverys as $id=> $delivery):?>
                    <tr>
                        <td>
                            <input type="radio" id="<?=$id?>" data-id="<?=$delivery[1]?>" onclick="changePrice(<?=$id?>)" value="<?=$id?>" name="delivery_id"
                            <?php
                            if ($id==1){
                                echo 'checked="checked"';
                            }
                            ?>
                            /><?=$delivery[0]?>

                        </td>
                        <td >￥<?=$delivery[1]?></td>

                        <td><?=$delivery[2]?></td>
                        <?php $delivery = $delivery[1]?>
                    </tr>
                   <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$pays as $id=> $pay):?>
                    <tr>
                        <td class="col1"><input value="<?=$id?>" <?php
                            if ($id==1){
                                echo 'checked="checked"';
                            }
                            ?>  type="radio" name="pay_id" /><?=$pay[0]?></td>
                        <td class="col2"><?=$pay[1]?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $prices =0;
                $goodsnum = 0;
                ?>

                <?php foreach ($goods as$good):?>

                <tr>
                    <td class="col1"><a href=""><img src="http://shop4admin.bigphp.cn.<?=$good->logo?>" alt="" /></a>  <strong><a href=""><?=$good->name?></a></strong></td>
                    <td class="col3">￥<?=$good->shop_price?></td>
                    <td class="col4"> <?=$carts[$good->id]?></td>
                    <?php $goodsnum +=$carts[$good->id]?>
                    <td class="col5"><span>￥<?=$good->shop_price*$carts[$good->id]?></span></td>
                    <?php $prices += $good->shop_price*$carts[$good->id]?>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$goodsnum?> 件商品，总商品金额：</span>
                                <em>￥<?=$prices?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>￥00.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="em1">￥20.00</em>
                            </li>
                            <li>
                                <span id="em" data-total="<?=$prices?>">应付总额：</span>
                                <em id="em2" >￥<?= $prices+20;?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
<!--        <a href=""><span>提交订单</span></a>-->
        <?php

        if (!empty($carts)){
            echo '<input type="submit" value="">';
        }
        ?>
        <span></span></input>

        <p>应付总额：<strong id="strong">￥<?= $prices+20;?>元</strong></p>
        <input type="hidden" name="total" value="<?=$prices+$delivery?>"/>

    </div>
    </form>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">
    function changePrice(id) {
         var price = $("#"+id+"").attr('data-id');
         var totalprice = $("#em").attr('data-total');
         var total = parseInt(totalprice)+parseInt(price) ;
           console.log(price);
           $("#em1").text(price);
           $("#em2").text(total);
         $("#strong").text(total);

    }
</script>
</body>
</html>
