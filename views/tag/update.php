<?php

$this->params['breadcrumbs'][] = [
    'label' => 'Управление тегами',
    'url' => '/tags',
    'template' => '<li><b class="execute-btn" title="Перейти к списку тегов">{link}</b></li>',
];
$this->title = $this->params['breadcrumbs'][] = 'Редактирование тега: ' . $model->name;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>