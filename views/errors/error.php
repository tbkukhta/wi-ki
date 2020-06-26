<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;

?>

<div class="site-error col-sm-12">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
        <span><?= nl2br(Html::encode($message)) ?></span>
        <span class="text-center"><a href="<?= Url::to('/') ?>">На главную</a>.</span>
    </div>
</div>