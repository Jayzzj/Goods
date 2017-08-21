<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id','sort'],'required' ],
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
            ['label','unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名称',
            'parent_id' => '父级id',
            'url' => '路由',
            'sort' => '排序',
        ];
    }

//    public static function getZNodes()
//    {
//        //返回Json格式数据
//        return Json::encode(
//        //合并数组
//            ArrayHelper::merge(
//            //设置一个顶级分类元素
//                [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],
//                //获取所有分类信息
//                self::find()->select(['id','name','parent_id'])->asArray()->all()
//            )
//        );
//    }

    public static function getMenu()
    {
        return
            //合并数组
            ArrayHelper::merge(
                //顶级菜单
                [['id'=>0,'parent_id'=>0,'label'=>'顶级菜单']],
                self::find()->select(['id','label','parent_id'])->where(['parent_id'=>0])->asArray()->all()

        );
    }

    public static function getPermissions()
    {

        return
            //合并数组
            ArrayHelper::merge(
            //顶级菜单
                ['0'=>'选择路由'],
                  ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name')


            );
    }


}
