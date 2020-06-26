<?php

use yii\helpers\Html;

$this->title = $this->params['breadcrumbs'][] = 'Профиль пользователя';

?>

<div class="panel panel-default">
    <div class="panel-heading">Пользователь: <?= Yii::$app->user->identity->first_name ?></div>
    <div class="panel-body">
        <div class="col-lg-12">
            <ul class="list-group">
                <li class="list-group-item">
                    <span>Логин</span>
                    <span class="badge"> <?= Yii::$app->user->identity->login ?></span>
                </li>
                <li class="list-group-item">
                    <span>Почта</span>
                    <span class="badge"> <?= Yii::$app->user->identity->email ?></span>
                </li>
                <li class="list-group-item">
                    <span>Имя</span>
                    <span class="badge"> <?= Yii::$app->user->identity->first_name ?></span>
                </li>
                <li class="list-group-item">
                    <span>Фамилия</span>
                    <span class="badge"> <?= Yii::$app->user->identity->last_name ?: 'Не указано' ?></span>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 text-right">
            <?= Html::a('Изменить', '/profile/settings', ['type' => 'button', 'class' => 'btn btn-primary execute-btn']) ?>
        </div>
    </div>
</div>