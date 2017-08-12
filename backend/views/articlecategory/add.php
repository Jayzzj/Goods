<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form -> field($acticlemodel,'name');
echo $form -> field($acticlemodel,'intro')->textarea();
echo $form -> field($acticlemodel,'sort');
echo $form -> field($acticlemodel,'status')->radioList([0=>'隐藏',1=>'正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn_info']);
\yii\bootstrap\ActiveForm::end();