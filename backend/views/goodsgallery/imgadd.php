<style>
    body{text-align:center}

</style>
<a href="<?php echo \yii\helpers\Url::to(['goods/index'])?>" class=" btn btn-info">返回主页</a>
<?php

$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'path')->hiddenInput();
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
        'onError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //设置图片回显SRC
        //$("#img").attr("src",data.fileUrl);
        //给隐藏域赋值
        //$("#goods-logo").val(data.fileUrl);
        }
        //添加图片的ajax请求
        $.getJSON("http://admin.yiishop.com/goodsgallery/add","path="+data.fileUrl+"&goods_id=$model->goods_id",function (data) {
        //console.log(data);
                //遍历得到的对象
                //追加到行标签span中
                    $("#span").append("<span><img  src="+data.path+" id="+data.goods+"/><button onclick='delImg("+data.id+")'  type='button' id="+data.id+"  class='btn btn-danger del_btn'>删除</button><br/></span>");
                });
  }
EOF
        ),
    ]
]);
yii\bootstrap\ActiveForm::end();
?>

<!--第一次进入相册-->
<span id="span" class="box">
    <?php  foreach($goodsGallery as $v):?>
        <span>
                <img src="<?= $v->path?>">
                <button type='button' onclick="delImg(<?=$v->id?>)" id="<?= $v->id?>" class='btn btn-danger del_btn'>删除</button><br/></span>
    <?php endforeach;?>
</span>

<!--声明一个单击删除函数-->
<script>

    //声明名一个删除的函数
    function delImg(id) {
        //弹窗提示是否删除
        var isdel = confirm("删除?");
        //返回true表示删除
        if (isdel === true){
            //利用Ajax请求根据id删除数据
            $.getJSON("del","id="+id+"",function (data){
                //判定数据库是否删除成功成功返回1
                if (data === 1){
                    //根据id获取对应的父节点并删除
                    $("#"+id+"").parent().remove();
                }
            })
        }

    }
</script>


