<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\ChangePasswordForm;

$this->params['breadcrumbs'][] = [
    'label' => 'Профиль пользователя',
    'url' => '/profile',
    'template' => '<li><b class="execute-btn" title="Перейти к описанию профиля">{link}</b></li>',
];
$this->title = $this->params['breadcrumbs'][] = 'Изменить профиль';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($user, 'login')->textInput() ?>
        <?= $form->field($user, 'email')->input('email') ?>
        <?= $form->field($user, 'first_name')->textInput() ?>
        <?= $form->field($user, 'last_name')->textInput() ?>
        <div class="form-group">
            <div class="col-sm-12 text-right">
                <?= Html::a('Отмена', '/profile', [
                    'type' => 'button',
                    'class' => 'btn btn-default execute-btn'
                ]) ?>
                <?= Html::button('Сменить пароль', [
                    'type' => 'button',
                    'class' => 'btn btn-warning',
                    'data-toggle' => 'modal',
                    'data-target' => '#changePassword',
                    'title' => 'Сменить пароль'
                ]) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary execute-btn']); ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

<?= $this->render('//modals/_change-password', ['changePasswordForm' => new ChangePasswordForm(Yii::$app->user->identity->getId())]) ?>