<?php


?>
    <h1>商品列表</h1>
    <a href="<?php echo \yii\helpers\Url::to(['goods/add'])?>" class="btn btn-success">商品添加</a>
    <form id="w0" class="form-inline" action="/goods/index" method="get" role="form">
        <div class="form-group field-articlesearchform-name">
            <label class="sr-only" for="articlesearchform-name">Name</label>
            <input type="text" id="articlesearchform-name" class="form-control" name="name" placeholder="商品名">
        </div>
        <div class="form-group field-articlesearchform-intro">
            <label class="sr-only" for="articlesearchform-intro">Intro</label>
            <input type="text" id="articlesearchform-intro" class="form-control" name="sn" placeholder="货号">
        </div>
        <div class="form-group field-articlesearchform-intro">
            <label class="sr-only" for="articlesearchform-intro">Intro</label>
            <input type="text" id="articlesearchform-intro" class="form-control" name="price_min" placeholder="$">
        </div>
        <div class="form-group field-articlesearchform-intro">
            <label class="sr-only" for="articlesearchform-intro">Intro</label>
            <input type="text" id="articlesearchform-intro" class="form-control" name="price_max" placeholder="$">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <table class="table">

        <tr>
            <th>商品名称</th>
            <th>货号</th>
            <th>LOGO图片</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>浏览次数</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?= $v->name?></td>
                <td><?= $v->sn?></td>
                <td><img src="<?= $v->logo?>" width="50"></td>
                <td><?= $v->shop_price?></td>
                <td><?= $v->stock?></td>
                <td>
                    <a class="btn btn-default glyphicon glyphicon-picture" href="<?= \yii\helpers\Url::to(['goodsgallery/imgadd','id'=>$v->id])?>">相册</a>
                    <a class="btn btn-warning glyphicon glyphicon-film" href="<?= \yii\helpers\Url::to(['goods/see','id'=>$v->id])?>">预览</a>
<a href="<?php echo \yii\helpers\Url::to(['goods/edit','id'=>$v->id])?>" class=" btn btn-info glyphicon glyphicon-edit">编辑</a>


                    <button id="<?=$v->id?>" onclick="delgoods(<?=$v->id?>)" class=" btn btn-danger glyphicon glyphicon-trash">删除</button>

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
    function delgoods(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("goods/del","id="+id+"",function (data) {
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
