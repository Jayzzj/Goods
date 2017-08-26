<?php
/**
 * @var $this yii\web\View
 */
?>
<h1>权限列表</h1>
<a class="btn btn-success" href="<?=\yii\helpers\Url::to(['rbac/parmissionadd'])?>">添加权限</a>
<table  id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($parmissions as $parmission):?>
    <tr>
        <td><?=$parmission->name; ?></td>
        <td><?=$parmission->description?></td>
        <td>
            <a class="btn btn-xs btn-warning glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['rbac/parmissionedit','name'=>$parmission->name])?>">编辑</a>
        <button  id="<?=$parmission->description?>" onclick="delpar('<?=$parmission->name?>','<?=$parmission->description?>')" class="btn btn-xs btn-danger glyphicon glyphicon-trash">删除</button>
        </td>
    </tr>

<?php endforeach;?>
    </tbody>
</table>
<?php
//加载分页的静态资源
//加载css文件

$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
//加载js文件   //depends 依赖关系
//$this->registerJsFile('@web/DataTables/media/js/jquery.js');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js',
    ['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(<<<JS

   $(document).ready( function () {
        $("#table_id_example").DataTable({
            language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
       });
    } );

JS
);
?>
<script type="text/javascript">
    //声明名一个删除的函数
    function delpar(id,name) {
        console.log(name);
        //弹窗提示是否删除
        //返回true表示删除
        if (confirm("删除?")){
            //利用Ajax请求根据id删除数据
            $.getJSON("parmissiondel","name="+id+"",function (data){
                //判定数据库是否删除成功成功返回1
                if (data === 1){
                    //根据id获取对应的父节点并删除
                    $("#"+name+"").parent().parent().remove();
                }
            })
        }

    }

</script>
