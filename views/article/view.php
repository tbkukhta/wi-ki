<?php

use app\models\Tag;
use app\models\User;
use yii\helpers\Url;
use app\models\Comment;

/* @var $modelUrl string */
/* @var $modelName string */
/* @var $userPermission boolean */
/* @var $comment Comment */
/* @var $breadcrumbs array|null */

$this->title = $model->name;
if (isset($breadcrumbs)) {
    foreach ($breadcrumbs as $breadcrumb) {
        $this->params['breadcrumbs'][] = $breadcrumb;
    }
}

?>

<div class="panel panel-default">
    <div class="panel-body">
            <ul class="nav nav-tabs">
                <li id="nav-dev" class="active">
                    <a href="#dev">DEV</a>
                </li>
                <li id="nav-business">
                    <a href="#business">BUSINESS</a>
                </li>
            </ul>
            <br>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert-danger alert fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <div id="comments-dev">
            <?php if ($comment->count_dev): ?>
                <?= $this->render('//comment/_filter', [
                    'tab' => Comment::COMMENTS_DEV,
                    'authors' => User::find()->all(),
                    'tags' => Tag::find()->all()
                ]) ?>
            <?php endif; ?>

            <?php if (empty($model->description) && empty($model->code) && !$userPermission): ?>
                <p>Описание пока отсутствует</p>
            <?php endif; ?>

            <div class="col-sm-6 box-mb">
                <div id="description-box">
                    <?= $this->render('//article/_description', [
                        'model' => $model,
                        'userPermission' => $userPermission
                    ]); ?>
                </div>
                <div class="row">
                    <?= $this->render('//comment/view', [
                        'comment' => $comment,
                        'tab' => Comment::COMMENTS_DEV,
                        'comments_count' => $comment->count_dev
                    ]); ?>
                </div>
            </div>

            <div class="col-sm-6 box-mb">
                <div id="code-box">
                    <?= $this->render('//article/_code', [
                        'model' => $model,
                        'userPermission' => $userPermission
                    ]); ?>
                </div>
            </div>

            <?php if ($modelName !== 'project' && $userPermission): ?>
                <div class="col-sm-12 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Настройки">
                            <i class="fa fa-fw fa-cog"></i> Настройки <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="<?= Url::toRoute($modelUrl . '/update') ?>" class="execute-btn" title="Редактировать" onclick="removeAlerts();">
                                    <i class="fa fa-fw fa-pencil"></i> Редактировать
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::toRoute($modelUrl . '/delete') ?>" title="Удалить"
                                   onclick="removeAlerts(); return confirmDelete('<?= $modelName === 'topic' ? 'тему' : 'раздел' ?>');">
                                    <i class="fa fa-fw fa-remove"></i> Удалить
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="comments-business" class="hidden">
            <?php if ($comment->count_business): ?>
                <?= $this->render('//comment/_filter', [
                    'tab' => Comment::COMMENTS_BUSINESS,
                    'authors' => User::find()->all(),
                    'tags' => Tag::find()->all()
                ]) ?>
            <?php endif; ?>

            <div id="title-box" class="col-sm-12">
                <?= $this->render('//article/_title', [
                    'model' => $model,
                    'userPermission' => $userPermission
                ]); ?>
            </div>

            <div class="col-sm-12">
                <div class="row">
                    <?= $this->render('//comment/view', [
                        'comment' => $comment,
                        'tab' => Comment::COMMENTS_BUSINESS,
                        'comments_count' => $comment->count_business
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>