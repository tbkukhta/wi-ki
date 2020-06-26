<?php

use app\models\Project;
use yii\bootstrap\ActiveForm;

/* @var $projectModel Project */

?>

<div id="addProject" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="col-sm-12 modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'create-project-form',
                'options' => ['class' => 'form-horizontal']
            ]) ?>
            <div class="modal-header col-sm-12">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Добавить новый проект</h4>
            </div>
            <div class="modal-body col-sm-12">
                <?= $form->field($projectModel, 'name') ?>
            </div>
            <div class="modal-footer col-sm-12">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>