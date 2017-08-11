<?php
/* @var $this yii\web\View */
?>


<table class="table">
    <a class="btn btn-success" href="<?= \yii\helpers\Url::to(['articlecategory/add'])?>">添加</a>
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
            <a class="btn btn-info" href="<?=\yii\helpers\Url::to(['articlecategory/edit','id'=>$v->id]) ?>">编辑</a>
            <a class="btn btn-danger" href="<?=\yii\helpers\Url::to(['articlecategory/del','id'=>$v->id]) ?>">删除</a>
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