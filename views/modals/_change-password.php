<?php

use app\models\ChangePasswordForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $changePasswordForm ChangePasswordForm */

?>

<div id="changePassword" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="col-sm-12 modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'change-password-form',
                'options' => ['class' => 'form-horizontal']
            ]) ?>
            <div class="col-sm-12 modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Смена пароля</h4>
            </div>
            <div class="col-sm-12 modal-body">
                <div class="col-sm-12 form-group">
                    <div class="col-sm-12">
                        <?= $form->field($changePasswordForm, 'oldPassword')->passwordInput() ?>
                    </div>
                    <div class="col-sm-12">
                        <?= $form->field($changePasswordForm, 'newPassword')->passwordInput() ?>
                    </div>
                    <div class="col-sm-12">
                        <?= $form->field($changePasswordForm, 'confirmNewPassword')->passwordInput() ?>
                    </div>
                    <div class="display-success text-success"></div>
                </div>
            </div>
            <div class="col-sm-12 modal-footer">
                <div class="col-sm-12">
                    <?= Html::a('Закрыть', null, [
                        'type' => 'button',
                        'class' => 'btn btn-default',
                        'data-dismiss' => 'modal'
                    ]) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>