<?php


?>
    <h1>品牌列表</h1>
<table class="table">
    <a href="<?php echo \yii\helpers\Url::to(['brand/add'])?>" class="btn btn-success">品牌添加</a>
    <tr>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌LOGO</th>
        <th>品牌排序</th>
        <th>品牌状态</th>
        <th>品牌操作</th>
    </tr>
    <?php foreach ($rows as $v):?>
    <tr>
        <td><?= $v->name?></td>
        <td><?= $v->intro?></td>
        <td><img src="<?= $v->logo?>" width="80"></td>
        <td><?= $v->sort?></td>
        <td><?= $v->status == 1?"显示":"隐藏";?></td>
        <td>
 <a href="<?php echo \yii\helpers\Url::to(['brand/edit','id'=>$v->id])?>"
 class=" btn btn-info glyphicon glyphicon-edit">编辑</a>
            <?php if (Yii::$app->user->can('brand/del')){?>
                <button id="<?="$v->id"?>" onclick="delbrand(<?=$v->id?>)" class=" btn btn-danger glyphicon glyphicon-trash">删除</button>
            <?php } ?>

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
    function delbrand(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("http://admin.yiishop.com/brand/del","id="+id+"",function (data) {
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
