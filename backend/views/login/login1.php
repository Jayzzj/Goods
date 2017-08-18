<?php


$form = \yii\bootstrap\ActiveForm::begin();
echo $form -> field($model,'username');
echo $form -> field($model,'password_hash')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn_info']);
\yii\bootstrap\ActiveForm::end();