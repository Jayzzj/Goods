<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','parent_id'],'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['parent_id','validateParent']//自定义验证分类
        ];
    }
    //自动义验证规则
    public function validateParent()
    {
        //判定修改自己修改为自己的儿子
        if ($this->parent_id == $this->id){
            //添加错误信息
            $this->addError('parent_id','不能添加到自己的下面');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '名称',
            'parent_id' => '父节点 ID',
            'intro' => '简介',
        ];
    }

    //获取商品分类选项
    public static function getCategoryItems()
    {
        $models = GoodsCategory::find()->all();
        $items = [0=>'顶级分类'];
        foreach ($models as $model){
            //$model  ['id'=>3,'name'=>'洗衣机',xxxx]
            $items[$model->id] = $model->name;
            //$items[3]='洗衣机';
        }
        //return [0=>'顶级分类',1=>'家用电器',2=>'大家电'];

        return $items;

        /*$items1 = [0=>'顶级分类'];
        $items2 = ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        return array_merge($items1,$items2);*/
    }

    //获取商品分类ztree数据
    public static function getZNodes()
    {
        //返回Json格式数据
        return Json::encode(
            //合并数组
            ArrayHelper::merge(
                //设置一个顶级分类元素
                [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],
                //获取所有分类信息
                self::find()->select(['id','name','parent_id'])->asArray()->all()
            )
        );
    }

    public function getGoodscategory()
    {
        //建立商品分类一对多的关系
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
