<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['id' => 'description-update-form']) ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-12 article-box">
                <div class="row">
                    <div class="col-sm-12 sample-title">
                        <div class="row">
                            <div class="col-sm-6">Описание кода</div>
                        </div>
                    </div>
                    <div class="col-sm-12 description-body">
                        <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>
                        <?= $form->field($model, 'description')->textarea([
                            'id' => 'description',
                            'value' => $model->description,
                            'maxlength' => true
                        ])->label(false) ?>
                    </div>
                    <div class="col-sm-12 form-group text-right">
                        <button type="button" class="btn btn-default" onclick="descriptionCancelUpdate();">Отмена</button>
                        <?= Html::submitButton('Сохранить', ['id' => 'description-submit-button', 'class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php

$this->registerJs('initCkeditor();');

?>