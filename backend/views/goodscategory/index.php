<?php


?>
<h1>商品分类列表</h1>
<a class="btn btn-success" href="<?=\yii\helpers\Url::to(['goodscategory/add'])?>">添加分类</a>
<table class="table">
    <tr>
        <th>序号</th>
        <th>分类名称</th>
        <th>简介</th>
    </tr>
    <?php foreach ($models as $v):?>
     <tr>
         <td><?=$v->id?></td>
         <td>
         <?php echo str_repeat('—',$v->depth).$v->name; ?>
         </td>

         <td>
             <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['goodscategory/edit','id'=>$v->id])?>">编辑</a>
             <a class="btn btn-danger glyphicon glyphicon-trash" href="<?=\yii\helpers\Url::to(['goodscategory/del','id'=>$v->id])?>">删除</a>
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