<?php

use app\models\User;

$this->params['breadcrumbs'][] = [
    'label' => 'Управление пользователями',
    'url' => '/users',
    'template' => '<li><b class="execute-btn" title="Перейти к списку пользователей">{link}</b></li>',
];
$this->title = $this->params['breadcrumbs'][] = 'Создание пользователя';

?>

<div class="panel panel-default">
    <?= $this->render('_form', ['model' => $model]) ?>
    <div class="panel-body">
        <h5><small>Пользователь будет создан с паролем по умолчанию "<?= User::DEFAULT_PASSWORD ?>".</small></h5>
    </div>
</div>