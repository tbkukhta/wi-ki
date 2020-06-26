<?php

$this->params['breadcrumbs'][] = [
    'label' => 'Управление пользователями',
    'url' => '/users',
    'template' => '<li><b class="execute-btn" title="Перейти к списку пользователей">{link}</b></li>',
];
$this->title = $this->params['breadcrumbs'][] = 'Редактирование пользователя: ' . $model->login;

?>

<div class="panel panel-default">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>