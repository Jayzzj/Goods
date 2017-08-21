<?php


namespace backend\controllers;


use backend\models\ParmissionFrom;
use backend\models\RoleFrom;
use yii\web\Controller;

class RbacController extends Controller
{
    public function actionParmissionadd()
    {
        $parmissions = new ParmissionFrom();
        //判定是否绑定成功
        if ($parmissions->load(\Yii::$app->request->post()) && $parmissions->validate()){
            //保存数据
                //调用表单模型添加权限并得到返回结果
             $parmissions->Parmissionsave();
                 //提示信息
                 \Yii::$app->session->setFlash('success','添加成功');
                 return $this->redirect(['rbac/parmissionindex']);
        }
        //显示页面
        return $this->render('parmissionadd',['parmissions'=>$parmissions]);
    }

    public function actionParmissionindex()
    {
        //获取所有权限
        $parmissions = \Yii::$app->authManager->getPermissions();

        return $this->render('parmissionindex',['parmissions'=>$parmissions]);
    }

    public function actionParmissionedit($name)
    {
        $parmissions = new ParmissionFrom();
        $parmission = \Yii::$app->authManager->getPermission($name);
         //给权限对象属性赋值
        $parmissions->name = $parmission->name;
        $parmissions->description = $parmission->description;
        //判定
        if ($parmissions->load(\Yii::$app->request->post()) && $parmissions->validate()){
            //保存数据
            //调用表单模型添加权限并得到返回结果
            $parmissions->Parmissionedit($parmission->name);
                //提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/parmissionindex']);

        }
        //显示页面
        return $this->render('parmissionadd',['parmissions'=>$parmissions]);
    }

    public function actionParmissiondel($name)
    {
//        echo 0;
//        实例权限对象
        $authManager = \Yii::$app->authManager;
        //获取一个权限对象
        $parmission = $authManager->getPermission($name);
        //删除权限
        if ($authManager->remove($parmission)){
            echo 1;
        }

    }

    public function actionRoleadd()
    {
        //实例角色对象
        $role = new RoleFrom();
        if ($role->load(\Yii::$app->request->post()) && $role->validate()){
            //调用模型添加角色
             if ($role->RoleAdd()){
                 \Yii::$app->session->setFlash('success','角色添加成功');
                 return $this->redirect(['rbac/roleindex']);
             }
        }
        return $this->render('roleadd',['role'=>$role]);
    }


    public function actionRoleindex()
    {
        //实例权限对象
        $authManager = \Yii::$app->authManager;
        //获取所有角色
        $roles = $authManager->getRoles();
        //分配到视图
        return $this->render('roleindex',['roles'=>$roles]);
    }


    public function actionRoleedit($name)
    {
        //实例化模型
        $roleFrom = new  RoleFrom();
        //获取指定角色对象
        $role = \Yii::$app->authManager->getRole($name);
        $roleFrom->name = $role->name;
        $roleFrom->description = $role->description;
        //获取该角色对应的所有权限
            $parmissions = \Yii::$app->authManager->getPermissionsByRole($name);
            //返回键名组成的索引数组,并赋值
             $roleFrom->parmission = array_keys($parmissions);
            if ($roleFrom->load(\Yii::$app->request->post()) && $roleFrom->validate()){

                //调用模型方法修改角色
                $roleFrom->Roleedit($name);

                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['rbac/roleindex']);
            }
        return $this->render('roleadd',['role'=>$roleFrom]);
    }

    public function actionRoledel($name)
    {

          //echo \Yii::$app->request->post('name');
        //获取当前角色对象删当前对象
        echo \Yii::$app->authManager->remove(\Yii::$app->authManager->getRole($name));

    }


}