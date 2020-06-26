<?php

use yii\helpers\Html;
use app\models\TopicItem;
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

    <?= $form->field($model, 'status')->dropDownList(TopicItem::getStatuses()) ?>

    <div class="form-group text-right">
        <?= Html::a('Отмена',
            '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug') . '/' .
            (isset($model->slug) ? $model->slug : 'topic-items'),
            ['type' => 'button', 'class' => 'btn btn-default execute-btn']
        ) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success execute-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>