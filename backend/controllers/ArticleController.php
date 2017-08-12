<?php
/**
 * Created by PhpStorm.
 * User: 周子健
 * Date: 2017/8/11
 * Time: 23:15
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class ArticleController extends Controller
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionIndex()
    {
        //接收get参数
        $data = \Yii::$app->request->get();
        //定义条件变量为空
        $title="";
        $intro = "";
        //判定该参数是否为空
        if (!empty($data['title'])){
            //拼接sql
            $title = ' and {{%article}}.`name` like '."'%{$data['title']}%'";
        }
        //判定该参数值是否为空
        if (!empty($data['intro'])){
            //拼接sql
            $intro = ' and {{%article}}.`intro` like '."'%{$data['intro']}%'";
        }
        $article = new Article();
        //连表查询
        $rows = Article::find()
            ->select('{{%article_category}}.name as category_name,{{%article}}.*')
            ->innerJoin('{{%article_category}}','{{%article}}.article_category_id = {{%article_category}}.id')
            ->where('{{%article}}.status != -1'."$title"."$intro");
        //调用分页工具
        $pages = new Pagination(['totalCount' =>$rows->count(), 'defaultPageSize' => '8']);
        //获取根据条件查询的数据
        $rst = $rows->offset($pages->offset)->limit($pages->limit)->asArray()->all();
//分配数据
        return $this->render('index',['rows'=>$rst,'article'=>$article,'pages'=>$pages]);
    }



    public function actionAdd()
    {
        $article_model = new Article();
        $articlecontent = new ArticleDetail();
        //实例request对象
        $requerst = \Yii::$app->request;
        //判定接收方式
        if ($requerst->isPost){
            //绑定数据
            $article_model->load($requerst->post());
            //绑定数据
            $articlecontent->load($requerst->post());
            //判定是否验证成功
            if ($article_model->validate()&&$articlecontent->validate()){
                //上传数据到数据库
                $article_model->save();
                //上传数据到数据库
                $articlecontent->save();
                //提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转主页
                return $this->redirect(Url::to(['article/index']));
            }else{
                //打印错误信息
                var_dump($article_model->getErrors());
            }
        }
        //获取分类所有信息
        $data = ArticleCategory::find()->where('status!=-1')->all();
        //分配数据及显示添加页面
        return $this->render('add',['article_model'=>$article_model,'data'=>$data,'articlecontent'=>$articlecontent]);
    }

    public function actionEdit($id)
    {
        $article_model = Article::findOne($id);
        $articlecontent =ArticleDetail::findOne($id);
        //实例request对象
        $requerst = \Yii::$app->request;
        //判定接收方式
        if ($requerst->isPost) {
            //绑定数据
            $article_model->load($requerst->post());
            //绑定数据
            $articlecontent->load($requerst->post());
            //判定是否验证成功
            if ($article_model->validate()&&$articlecontent->validate()){
                //上传数据到数据库
                $article_model->save();
                //上传数据到数据库
                $articlecontent->save();
                //提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转主页
                return $this->redirect(Url::to(['article/index']));
            }else{
                //打印错误信息
                var_dump($article_model->getErrors());
            }
        }
        //获取分类所有信息
        $data = ArticleCategory::find()->where('status!=-1')->all();
        //分配数据及显示添加页面
        return $this->render('add',['article_model'=>$article_model,'data'=>$data,'articlecontent'=>$articlecontent]);
    }

    public function actionDel($id)
    {
        $article = Article::findOne($id);

        $article->status = -1;

        if ($article->validate()){
            $article->save();
        }else{
            var_dump($article->getErrors());exit;
        }

        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(Url::to(['index']));

    }

}