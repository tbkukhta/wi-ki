<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="panel-body">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea([
        'id' => 'description',
        'style' => 'resize:vertical'
    ]) ?>
    <?= $form->field($model, 'code')->textarea([
        'id' => 'code',
        'rows' => 10,
        'style' => 'resize:vertical'
    ]) ?>
    <div class="form-group text-right">
        <?= Html::a('Отмена', '/project/' . Yii::$app->request->get('slug'), [
            'type' => 'button',
            'class' => 'btn btn-default execute-btn'
        ]) ?>
        <?= Html::submitButton('Сохранить', [
            'type' => 'button',
            'class' => 'btn btn-success execute-btn'
        ]) ?>
        <?= Html::a('Удалить', '/project/' . Yii::$app->request->get('slug') . '/delete', [
            'type' => 'button',
            'class' => 'btn btn-danger execute-btn',
            'onclick' => 'confirmDelete("проект");'
        ]); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>