
<h1>角色列表</h1>
<a class="btn btn-success" href="<?=\yii\helpers\Url::to(['rbac/roleadd'])?>">添加角色</a>
<table class="table" id="table" >
    <tr>

        <th>角色</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
    <tr >
        <td><?=$role->name; ?></td>
        <td><?=$role->description?></td>
        <td>
            <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['rbac/roleedit','name'=>$role->name])?>">编辑</a>
            <button name="<?=$role->name?>" id="<?=$role->name ?>" onclick="delrole('<?=$role->name?>')" class="btn btn-danger">删除</button>
        </td>
    </tr>
<?php endforeach;?>
</table>
<?php // $url = \yii\helpers\Url::to(['roledel']);
///**
// * @var $this \yii\web\View
// */
//
////监听表格的单击事件
//$this->registerJs(new \yii\web\JsExpression(
//        <<<JS
//      // 获取table对象
//      $("#table").on('click','.del_btn',function() {
//        //判定是否删除图片
//        if (confirm('确定删除吗？！')){
//             //寻找当前对象的父节点
//             var tr = $(this).closest('tr');
//             //获取当前节点的id值
//             var name = tr.attr('data-name');
//             //发起ajax请求
//             $.post("{$url}",{name:name},function(data) {
//                 console.log(data);
//               //判定是否删除成功
//               // if (data === 1){
//               //     //删除当前节点的tr
//               //     tr.remove();
//               // }else {
//               //     console.log(0);
//               // }
//             })
//        }
//
//
//      })
//JS
//
//));

?>
<script>

    //声明名一个删除的函数
    function delrole(name) {
//        console.log()
        //弹窗提示是否删除
        //返回true表示删除
        if (confirm("删除?")){
            //利用Ajax请求根据id删除数据
            $.getJSON("roledel","name="+name+"",function (data){
                //console.log(data);

                //判定数据库是否删除成功成功返回1
                if (data === 1){
                    //根据id获取对应的父节点并删除
                    $("#"+name+"").parent().parent().remove();
                }
            })
        }

    }


</script>