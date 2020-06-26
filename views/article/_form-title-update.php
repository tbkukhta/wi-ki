<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="article-title">
    <?php $form = ActiveForm::begin(['id' => 'title-update-form']) ?>
    <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>
    <?= $form->field($model, 'name')->textInput([
        'id' => 'title',
        'value' => $model->name,
        'maxlength' => true
    ])->label(false) ?>
    <button type="button" class="btn btn-default" onclick="titleCancelUpdate();">Отмена</button>
    <?= Html::submitButton('Сохранить', ['id' => 'title-submit-button', 'class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
</div>