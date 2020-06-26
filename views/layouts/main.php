<?php

use app\assets\AppAsset;
use app\helpers\ArticleHelper;
use app\helpers\PermissionHelper;
use app\models\Project;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $content string */

AppAsset::register($this);

$project = Project::findModel();

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <?= $this->render('//layouts/blocks/_head') ?>

    <body class="cm-no-transition cm-1-navbar">
    <?php $this->beginBody() ?>
    <div id="cm-menu">
        <nav class="cm-navbar cm-navbar-primary">
            <div class="cm-flex project-name">
            <span id="project-title"><?= $project->name ?></span>
                <?php if (PermissionHelper::can('updateProject') || ArticleHelper::authorPermission($project->author_id)): ?>
                    <a class="project-name-pencil execute-btn" title="Редактировать проект"
                       href="<?= Url::toRoute('/project/' . $project->slug . '/update') ?>">
                        <i class="fa fa-fw fa-pencil"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div id="hide-menu">
                <div id="hide-menu-button" class="btn btn-primary md-menu-white" data-toggle="cm-menu"></div>
            </div>
        </nav>
        <div class="pos-unset" id="cm-menu-content">
            <div class="pos-unset" id="cm-menu-items-wrapper">
                <div id="cm-menu-scroller">
                    <div id="menu-box">
                        <?= $this->render('//layouts/blocks/_menu', ['project' => $project]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <header id="cm-header">
        <nav class="cm-navbar cm-navbar-primary">
            <div class="cm-flex">
                <h1 id="main-title">
                    <?php if (isset($this->params['breadcrumbs'])): ?>
                        <?= Breadcrumbs::widget([
                            'homeLink' => [
                                'label' => $project->name,
                                'url' => '/project/' . $project->slug,
                                'template' => '<li><b class="execute-btn" title="Перейти на домашнюю страницу проекта">{link}</b></li>'
                            ],
                            'links' => $this->params['breadcrumbs']
                        ]); ?>
                    <?php else: ?>
                        <?= $this->title ?>
                    <?php endif; ?>
                </h1>
            </div>
            <?= $this->render('//layouts/blocks/_userbox') ?>
        </nav>
    </header>
    <div id="global">
        <div class="container-fluid">
            <?= $content ?>
        </div>
        <?= $this->render('//layouts/blocks/_footer') ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>