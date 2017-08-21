<?php



$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
if ($model->password_hash){
   $model->password_hash = "";
}
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email');
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'启用','0'=>"失效"]);
echo  $form->field($roleFrom,'parmission',['inline'=>true])->checkboxList(yii\helpers\ArrayHelper::map($Roles,'name','description'));

echo yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
yii\bootstrap\ActiveForm::end();