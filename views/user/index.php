<?php

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $dataProvider ActiveDataProvider */

$this->title = $this->params['breadcrumbs'][] = 'Управление пользователями';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert-danger alert fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => '{begin}-{end}/{totalCount}',
            'columns' => [
                'login',
                'first_name',
                'last_name',
                'email',
                [
                    'attribute' => 'role',
                    'value' => function ($model) {
                        return User::getRoles()[$model->role];
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return User::getStatuses()[$model->status];
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="text-right">{update} {delete}</div>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<button type="button" class="btn btn-default execute-btn"><i class="fa fa-fw fa-pencil"></i></button>',
                                '/user/update/' . $model->id,
                                ['title' => 'Редактировать']
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<button type="button" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>',
                                '/user/delete/' . $model->id,
                                ['onclick' => 'return confirmDelete("пользователя");', 'title' => 'Удалить']
                            );
                        }
                    ]
                ]
            ]
        ]); ?>
        <div class="inline-block float-right">
            <?= Html::a('Добавить пользователя', '/user/create', ['type' => 'button', 'class' => 'btn btn-primary execute-btn']) ?>
        </div>
    </div>
</div>