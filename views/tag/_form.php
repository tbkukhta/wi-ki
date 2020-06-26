<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="comment-tag-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group text-right">
        <?= Html::a('Отмена', '/tags', ['type' => 'button', 'class' => 'btn btn-default execute-btn']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary execute-btn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>