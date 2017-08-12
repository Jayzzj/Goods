<?php


$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($article_model,'name');
echo $form->field($article_model,'intro')->textarea();
echo $form->field($article_model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($data,'id','name'));
echo $form->field($article_model,'sort');
echo $form->field($article_model,'status')->radioList([0=>'隐藏',1=>'显示']);
//echo $form->field($articlecontent,'content')->textarea();
echo $form->field($articlecontent,'content')->widget('kucha\ueditor\UEditor',[]);

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();



