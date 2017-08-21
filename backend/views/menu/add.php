<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form -> field($model,'label');
echo $form -> field($model,'parent_id')->dropDownList(yii\helpers\ArrayHelper::map(\backend\models\Menu::getMenu(),'id','label'));
echo $form -> field($model,'url')->dropDownList(\backend\models\Menu::getPermissions());
echo $form -> field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn_info']);
\yii\bootstrap\ActiveForm::end();