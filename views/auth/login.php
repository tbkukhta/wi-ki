<?php

use yii\bootstrap\ActiveForm;
use app\models\LoginForm;
use yii\helpers\Html;

/* @var $loginForm LoginForm */

$this->title = 'Авторизация';

?>

<div class="login-form col-sm-6 col-md-4 col-lg-3">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'validateOnBlur' => false,
        'validateOnChange' => false
    ]); ?>

    <div class="col-xs-12">
        <?= $form->field($loginForm, 'login', [
            'template' => '<div class="input-group"><div class="input-group-addon"><i class="fa fa-fw fa-user"></i></div>{input}</div><div>{error}</div>'
        ])->textInput([
            'autofocus' => true,
            'class' => 'form-control',
            'placeholder' => 'Логин'
        ])->label(false) ?>

        <?= $form->field($loginForm, 'password', [
            'template' => '<div class="input-group"><div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>{input}</div><div>{error}</div>'
        ])->passwordInput([
            'id' => 'login-password',
            'class' => 'form-control',
            'placeholder' => 'Пароль'
        ])->label(false) ?>
    </div>

    <div class="col-xs-6">
        <div class="checkbox">
            <label><input type="checkbox" id="display-password">Показать пароль</label>
        </div>

        <?= $form->field($loginForm, 'rememberMe')->checkbox([
            'template' => '<div class="checkbox"><label>{input}{label}</label></div><div>{error}</div>',
            'label' => 'Запомнить меня'
        ]) ?>
    </div>

    <div class="col-xs-6">
        <?= Html::submitButton('Войти', [
            'class' => 'btn btn-block btn-primary execute-btn',
            'id' => 'login-button',
            'label' => 'Войти'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$script = <<<JS
    /**
     * Show/hide password on login page
     */
    $('#display-password').on('change', function() {
      $('#login-password').attr('type', this.checked ? 'text' : 'password');
    });
JS;
$this->registerJs($script);

?>