<?php


$form = yii\bootstrap\ActiveForm::begin();
echo  $form->field($role,'name');
echo  $form->field($role,'description');
echo  $form->field($role,'parmission',['inline'=>true])->checkboxList($role->Parmissionsitems);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();