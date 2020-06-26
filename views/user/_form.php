<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="panel-body">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'role')->dropDownList(User::getRoles()) ?>
    <?= $form->field($model, 'status')->dropDownList(User::getStatuses()) ?>
    <div class="form-group text-right">
        <?= Html::a('Отмена', '/users', ['type' => 'button', 'class' => 'btn btn-default execute-btn']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary execute-btn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>