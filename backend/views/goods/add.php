<?php


use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//echo $form->field($model,'sn');
//echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'logo')->hiddenInput();
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //设置图片回显SRC
        $("#img").attr("src",data.fileUrl);
        //给隐藏域赋值
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img']);
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

//调用模型的方法获取所有的分类信息
$zNodes = \backend\models\GoodsCategory::getZNodes();
//判定商品分类ID是否存在
if (!$model->goods_category_id){
    $model->goods_category_id = 'null';
}
//加载ztree的静态资源
//加载css文件
//$this->registerCssFile('@web/zTree/css/demo.css');
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件   //depends 依赖关系
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//加载js代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
 var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",//父节点对应名称
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event, treeId, treeNode){
                    console.log(treeNode.id);
                    //赋值给parent_id
                    $("#goods-goods_category_id").val(treeNode.id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
       var zNodes = {$zNodes};
 
        
       zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       //展开所有节点
       zTreeObj.expandAll(true);
       //修改功能   根据当前分类的parent_id选中节点
       var node = zTreeObj.getNodeByParam("id",{$model->goods_category_id}, null);//根据id获取节点
       zTreeObj.selectNode(node);
JS
));
//获取品牌分类信息
$brand = \backend\models\Goods::getBrand();
echo $form->field($model,'brand_id')->dropDownList(yii\helpers\ArrayHelper::map($brand,'id','name'));

//}
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale')->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'sort');
//Uedir
echo $form->field($goodsIntro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn_info']);
\yii\bootstrap\ActiveForm::end();