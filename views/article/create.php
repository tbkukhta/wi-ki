<?php

/* @var $title string */
/* @var $breadcrumbs array */

$this->title = $title;
foreach ($breadcrumbs as $breadcrumb) {
    $this->params['breadcrumbs'][] = $breadcrumb;
}

?>

<div class="panel panel-default">
    <?= $this->render('//' . Yii::$app->controller->id . '/_form', ['model' => $model]) ?>
</div>

<?php

$this->registerJs('initCkeditor();');

?>