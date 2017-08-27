<?php


namespace frontend\controllers;


use frontend\models\Address;
use yii\web\Controller;

class AddressController  extends Controller
{
    public function actionIndex()
    {

        $model = new Address();
        //获取所有数据
        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()){
            $model->member_id = \Yii::$app->user->id;
            //保存数据
            $model->save();
            //判定是否勾选默认地址
            if ($model->status){
                //获取id
                  $id= $model->id;
//                  var_dump($id);exit;
                //修改所有地址为1的数据排除才修改的那个
                Address::updateAll(['status'=>0],'id!='.$id);
            }
            return $this->refresh();
        }
        //获取所有地址信息
        $model = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();

       return $this->render('address',['model'=>$model]);
    }


    public function actionStatus($id)
    {
        //获取数据
        $model = Address::findOne($id);
        //修改状态值
        $model->status = 1;
        $model->save();
        //修改地址状态排除设为默认那个
        Address::updateAll(['status'=>0],'id!='.$id);
        return $this->redirect(['index']);
    }

    public function actionEdit($id)
    {
        //获取对象
        $address = Address::findOne($id);
        //判定
        if ($address->load(\Yii::$app->request->post(),'') && $address->validate()){
            $address->save();
            return $this->redirect(['address/index']);
        }
        //获取所有
        $model = Address::find()->all();
        return $this->render('address',['model'=>$model,'address'=>$address]);

    }

    public function actionDel($id)
    {
        echo Address::findOne($id)->delete();

    }

}