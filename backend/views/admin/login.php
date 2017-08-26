<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to login:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'code')->widget(\yii\captcha\Captcha::className(), [
                'captchaAction'=>'admin/code',
                'template' => '<div class="row"><div class="col-lg-9 col-md-9">{input}</div><div class="col-lg-3 col-md-3">{image}</div></div>'
            ]) ?>
            <?=$form->field($model, 'rememberMe')->checkbox([0])?>
            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
