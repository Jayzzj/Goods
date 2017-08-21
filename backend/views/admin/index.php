<?php
/* @var $this yii\web\View */

?>
<h1>管理员列表</h1>
    <a class="btn btn-success" href="<?=\yii\helpers\Url::to(['admin/add']) ?>">添加管理员</a>
    <a class="btn btn-success" href="<?=\yii\helpers\Url::to(['admin/modify']) ?>">修改密码</a>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>创建时间</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?= $v->id?></td>
                <td><?= $v->username?></td>
                <td><?= $v->email?></td>

                <td><?= date('Y-m-d H:i:s',$v->created_at)?></td>
                <td><?= $v->last_login_time?date('Y-m-d H:i:s',$v->last_login_time):"无记录"?></td>
                <td><?= $v->last_login_ip?long2ip($v->last_login_ip):"无记录"?></td>
                <td><?= $v->status==1?"启用":"禁用"?></td>
                <td>
                    <a class="btn btn-info glyphicon glyphicon-edit" href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$v->id]) ?>">编辑</a>
                    <button id="<?=$v->id?>" onclick="deladmin(<?=$v->id?>)" class="btn btn-danger glyphicon glyphicon-trash" href="<?=\yii\helpers\Url::to(['admin/del','id'=>$v->id]) ?>">删除</button>
                </td>
            </tr>
        <?php endforeach;?>

    </table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pages,//控制器赋值的分页变量
    'maxButtonCount' => "10",//每页最多显示按钮个数
    'prevPageLabel' => '<<',//改变上一页按钮的字符，设置为fase不显示
    'nextPageLabel' => '>>',//改变下一页按钮的字符，设置为fase不显示
    'firstPageLabel' => '首页',//首页，默认不显示
    'lastPageLabel' => '末页',//尾页，默认不显示
    'hideOnSinglePage' => false,//如果你的数据过少，不够2页，默认不显示分页，可以设置为false
//'options' => ['class' => '样式']//设置样式
])?>
<script>
    function deladmin(id) {
        if (confirm("确定删除吗?")){
            $.getJSON("http://admin.yiishop.com/admin/del","id="+id+"",function (data) {
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
