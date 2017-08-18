<?php

?>

<!--//图片轮播是使用bootstrap提供的js组件 在使用中如果需要对其他参数进行修改。例如自动轮播间隔时间，暂停事件等需要使用选择器选定对象进行操作-->


<!--//js设置其他参数 $("#carousel-example-generic").carousel({ interval:2000 })-->
<div class="container" id="box">


    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="width: 1100px;"> <!-- Indicators --> <ol class="carousel-indicators"> <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li> <li data-target="#carousel-example-generic" data-slide-to="1"></li> </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <img src="http://admin.yiishop.com/upload/e1/44/e14499fd9d469f8a4adaf35a849537255b249243.jpg" alt="...">
            </div>

            <?php  foreach ($imgs as $v):?>
                <div class="item">
                    <img src="<?= $v->path?>" alt="">
                </div>
            <?php endforeach;?>
        </div>

        <!-- Controls --> <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>

<!--    //js设置其他参数 $("#carousel-example-generic").carousel({ interval:2000 })-->
    <?= $intro->content?>
</div>