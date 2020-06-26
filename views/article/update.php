<?php

/* @var $title string */
/* @var $breadcrumbs array */

$this->title = $title . ': ' . $model->name;
foreach ($breadcrumbs as $breadcrumb) {
    $this->params['breadcrumbs'][] = $breadcrumb;
}

?>

<div class="panel panel-default">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert-danger alert fade in">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
    <?= $this->render('//' . Yii::$app->controller->id . '/_form', ['model' => $model]) ?>
</div>

<?php

$this->registerJs('initCkeditor();');

?>