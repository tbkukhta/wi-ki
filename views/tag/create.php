<?php

$this->params['breadcrumbs'][] = [
    'label' => 'Управление тегами',
    'url' => '/tags',
    'template' => '<li><b class="execute-btn" title="Перейти к списку тегов">{link}</b></li>',
];
$this->title = $this->params['breadcrumbs'][] = 'Создание тега';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-sm-12">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>
    </div>
</div>