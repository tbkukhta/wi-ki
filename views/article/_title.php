<?php

/* @var $userPermission boolean */

?>

<div class="article-title">
    <span id="article-name"><?= yii\helpers\Html::encode($model->name) ?></span>
    <?php if ($userPermission): ?>
        <a href="#update" title="Редактировать название" onclick="titleUpdate('<?= $model->id ?>');">
            <i class="fa fa-fw fa-pencil"></i>
        </a>
    <?php endif; ?>
</div>