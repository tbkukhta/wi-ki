<?php

use app\models\Project;
use yii\helpers\Url;
use app\helpers\PermissionHelper;
use app\helpers\ArticleHelper;

$this->title = 'Управление проектами';

?>

<div id="projects-main-box" class="col-xs-12 col-sm-12 col-md-12">
    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="panel panel-default project-box">
                    <a href="<?= Url::to('/project/' . $project->slug) ?>" class="app-tooltip no-underline execute-btn"
                       data-placement="bottom" data-original-title="Перейти к проекту">
                        <?= $project->name ?>
                    </a>
                    <?php if (PermissionHelper::can('updateProject') || ArticleHelper::authorPermission($project->author_id)): ?>
                        <a href="<?= Url::to('/project/' . $project->slug . '/update') ?>" class="app-tooltip execute-btn"
                           data-placement="top" data-original-title="Редактировать">
                            <i class="fa fa-fw"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (PermissionHelper::can('createProject')): ?>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="project-box-add" data-toggle="modal" data-target="#addProject" title="Добавить">
                <span class="big-plus">+</span>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->render('//modals/_create-project', ['projectModel' => new Project()]) ?>