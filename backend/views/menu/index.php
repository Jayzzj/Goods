<h1>菜单列表</h1>
<a class="btn btn-success" href="<?=\yii\helpers\Url::to(['menu/add'])?>">添加菜单</a>
<table class="table" >
    <tr>
        <th>名称</th>
        <th>url</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?php  if ($model->parent_id !=0){
                echo str_repeat('—',2). $model->label;
            }else{
                echo  $model->label;
                }
                ?>
            </td>
            <td><?=$model->url?$model->url:""?></td>
            <td><?=$model->sort?></td>
            <td>
                <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$model->id])?>">编辑</a>
                <button onclick="delmenu(<?=$model->id?>)" class="btn btn-danger glyphicon glyphicon-trash" id="<?=$model->id?>">删除</button>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<script>

    //声明名一个删除的函数
    function delmenu(id) {
        //弹窗提示是否删除
        var isdel = confirm("删除?");

        //返回true表示删除
        if (isdel === true){
            //利用Ajax请求根据id删除数据
            $.getJSON("del","id="+id+"",function (data){
                //判定数据库是否删除成功成功返回1
                if (data === 1){
                    //根据id获取对应的父节点并删除
                    $("#"+id+"").parent().parent().remove();
                }else {
                    alert("该节点下有子节点不能删除");
                }
            })
        }

    }


</script>

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