<?php
namespace backend\filters;

class RbacFilter extends \yii\base\ActionFilter
{

    public function beforeAction($action)
    {
        //当前访问路由是否有权限
        if (!\Yii::$app->user->can($action->uniqueId)){
            //如果没有登录跳转登录页面
            if (\Yii::$app->user->isGuest){
                //寻找控制器跳转登录页面send()
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //没有权限提示信息
            throw new \yii\web\HttpException(403,'你没有权限执行该操作');

        }
        return parent::beforeAction($action);
    }


}