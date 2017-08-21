<?php


$form = yii\bootstrap\ActiveForm::begin();
echo  $form->field($parmissions,'name');
echo  $form->field($parmissions,'description');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();