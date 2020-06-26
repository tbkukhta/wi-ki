<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['id' => 'code-update-form']) ?>
    <div class="col-sm-12 article-box">
        <div class="row">
            <div class="col-sm-12 sample-title">
                <div class="row">
                    <div class="col-sm-6">Пример кода</div>
                </div>
            </div>
            <div class="col-sm-12 description-body">
                <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>
                <?= $form->field($model, 'code')->textarea([
                    'id' => 'code',
                    'value' => $model->code,
                    'maxlength' => true,
                    'rows' => 20,
                    'style' => 'resize:vertical'
                ])->label(false) ?>
            </div>
            <div class="col-sm-12 form-group text-right">
                <button type="button" class="btn btn-default" onclick="codeCancelUpdate();">Отмена</button>
                <?= Html::submitButton('Сохранить', ['id' => 'code-submit-button', 'class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>