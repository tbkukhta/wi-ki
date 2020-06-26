<?php

use yii\helpers\Url;
use app\helpers\PermissionHelper;

?>

<div class="dropdown pull-right">
    <button type="button" class="btn btn-primary md-account-circle-white" data-toggle="dropdown" title="Меню"></button>
    <ul class="dropdown-menu">
        <li class="disabled text-center">
            <a style="cursor: default;">
                <strong><?= Yii::$app->user->identity->first_name ?></strong>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a class="execute-btn" href="<?= Url::toRoute('/profile') ?>"><i class="fa fa-fw fa-user"></i> Профиль</a>
        </li>
        <?php if (PermissionHelper::canGroup('user')): ?>
            <li>
                <a class="execute-btn" href="<?= Url::to('/users') ?>"><i class="fa fa-fw fa-users"></i> Пользователи</a>
            </li>
        <?php endif; ?>
        <?php if (PermissionHelper::canGroup('tag')): ?>
            <li>
                <a class="execute-btn" href="<?= Url::to('/tags') ?>"><i class="fa fa-fw fa-tags"></i> Тэги</a>
            </li>
        <?php endif; ?>
        <li>
            <a class="execute-btn" href="<?= Url::to('/projects') ?>"><i class="fa fa-fw fa-briefcase"></i> Проекты</a>
        </li>
        <li>
            <a class="execute-btn" href="<?= Url::toRoute('/logout') ?>"><i class="fa fa-fw fa-sign-out"></i> Выйти</a>
        </li>
    </ul>
</div>