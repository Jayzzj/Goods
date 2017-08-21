<?php
/* @var $this yii\web\View */
?>
<h1>文章分类列表</h1>

<table class="table">
    <a class="btn btn-success" href="<?= \yii\helpers\Url::to(['articlecategory/add'])?>">添加文章分类</a>
    <tr>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>文章排序</th>
        <th>文章状态</th>
        <th>文章操作</th>
    </tr>
    <?php foreach ($rows as $v):?>
    <tr>
        <td><?= $v->name?></td>
        <td><?= $v->intro?></td>
        <td><?= $v->sort?></td>
        <td><?= $v->status==1?"正常":"隐藏"?></td>
        <td>
            <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['articlecategory/edit','id'=>$v->id]) ?>">编辑</a>

            <button id="<?=$v->id?>" onclick="delarticlecategory(<?=$v->id?>)" class="btn btn-danger glyphicon glyphicon-trash">删除</button>
        </td>
    </tr>
    <?php endforeach;?>

</table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,//控制器赋值的分页变量
    'maxButtonCount' => "10",//每页最多显示按钮个数
    'prevPageLabel' => '上页',//改变上一页按钮的字符，设置为fase不显示
    'nextPageLabel' => '下页',//改变下一页按钮的字符，设置为fase不显示
    'firstPageLabel' => '首页',//首页，默认不显示
    'lastPageLabel' => '末页',//尾页，默认不显示
    'hideOnSinglePage' => false,//如果你的数据过少，不够2页，默认不显示分页，可以设置为false
//'options' => ['class' => '样式']//设置样式
])?>

<script>
    function delarticlecategory(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("http://admin.yiishop.com/articlecategory/del","id="+id+"",function (data) {
//
                if (data ===1){
                    //删除当前节点的父节点
                    $("#"+id+"").closest('tr').remove();
                }else {
                    console.log(data);
                }
            })
        }
    }
</script>
