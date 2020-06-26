<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\PermissionHelper;
use app\helpers\ArticleHelper;
use app\models\Project;
use app\models\Topic;

/* @var $dataProvider ActiveDataProvider */

$this->title = $this->params['breadcrumbs'][] = 'Управление темами';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert-danger alert fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($dataProvider->totalCount): ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '{begin}-{end}/{totalCount}',
                'columns' => [
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->name,
                                '/project/' . Yii::$app->request->get('slug') . '/' . $model->slug . '/topic-items',
                                ['type' => 'button', 'class' => 'execute-btn', 'title' => 'Перейти к разделам']);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return Topic::getStatuses()[$model->status];
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-right">{view} {update} {delete}</div>',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<button type="button" class="btn btn-info execute-btn"><i class="fa fa-fw fa-eye"></i></button>',
                                    '/project/' . Yii::$app->request->get('slug') . '/' . $model->slug,
                                    ['title' => 'Смотреть']);
                            },
                            'update' => function ($url, $model) {
                                return (PermissionHelper::can('updateTopic') || ArticleHelper::authorPermission($model->author_id))
                                    ? Html::a('<button type="button" class="btn btn-yellow execute-btn"><i class="fa fa-fw fa-pencil"></i></button>',
                                        '/project/' . Yii::$app->request->get('slug') . '/' . $model->slug . '/update',
                                        ['title' => 'Редактировать'])
                                    : '';
                            },
                            'delete' => function ($url, $model) {
                                return (PermissionHelper::can('deleteTopic') || ArticleHelper::authorPermission($model->author_id))
                                    ? Html::a('<button type="button" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>',
                                        '/project/' . Yii::$app->request->get('slug') . '/' . $model->slug . '/delete',
                                        ['onclick' => 'return confirmDelete("тему");', 'title' => 'Удалить'])
                                    : '';
                            }
                        ]
                    ]
                ]
            ]); ?>
        <?php else: ?>
            <div class="inline-block">Тем пока нет.</div>
        <?php endif; ?>

        <?php if (PermissionHelper::can('createTopic') || ArticleHelper::authorPermission(Project::findModel()->author_id)): ?>
            <div class="inline-block float-right">
                <?= Html::a('Добавить тему',
                    '/project/' . Yii::$app->request->get('slug') . '/topic/create',
                    ['type' => 'button', 'class' => 'btn btn-primary execute-btn'])
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>