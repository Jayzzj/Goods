<?php
/* @var $this yii\web\View */

?>
<h1>文章列表</h1>
    <a class="btn btn-success" href="<?= \yii\helpers\Url::to(['article/add'])?>">添加文章</a>
    <form id="w0" class="form-inline" action="/article/index" method="get" role="form"><div class="form-group field-articlesearchform-name">
            <label class="sr-only" for="articlesearchform-name">Name</label>
            <input type="text" id="articlesearchform-name" class="form-control" name="name" placeholder="名称">
        </div><div class="form-group field-articlesearchform-intro">
            <label class="sr-only" for="articlesearchform-intro">Intro</label>
            <input type="text" id="articlesearchform-intro" class="form-control" name="intro" placeholder="简介">
        </div><button type="submit" class="btn btn-default">搜索</button></form>


    <table class="table">
        <tr>
            <th>文章名称</th>
            <th>文章简介</th>
            <th>文章排序</th>
            <th>文章状态</th>
            <th>文章类别</th>
            <th>创建时间</th>
            <th>文章内容</th>
            <th>文章操作</th>
        </tr>
        <?php foreach ($rows as $v):?>
            <tr>
                <td><?= $v->name?></td>
                <td><?= $v->intro?></td>
                <td><?= $v->sort?></td>
                <td><?= $v->status==1?"正常":"隐藏"?></td>
                <td><?= $v->category->name?></td>
                <td><?= $v->create_time?></td>
                <td><a class="btn btn-default" href="<?=\yii\helpers\Url::to(['articledetail/index','id'=>$v->id])?>">查看内容</a></td>
                <td>
                    <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['article/edit','id'=>$v->id]) ?>">编辑</a>
                    <button id="<?=$v->id?>" onclick="delarticle(<?=$v->id?>)" class="btn btn-danger glyphicon glyphicon-trash">删除</button>
                </td>
            </tr>
        <?php endforeach;?>

    </table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pages,//控制器赋值的分页变量
    'maxButtonCount' => "10",//每页最多显示按钮个数
    'prevPageLabel' => '上页',//改变上一页按钮的字符，设置为fase不显示
    'nextPageLabel' => '下页',//改变下一页按钮的字符，设置为fase不显示
    'firstPageLabel' => '首页',//首页，默认不显示
    'lastPageLabel' => '末页',//尾页，默认不显示
    'hideOnSinglePage' => false,//如果你的数据过少，不够2页，默认不显示分页，可以设置为false
//'options' => ['class' => '样式']//设置样式
])?>
<script>
    function delarticle(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("del","id="+id+"",function (data) {
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