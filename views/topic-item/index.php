<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\PermissionHelper;
use app\helpers\ArticleHelper;
use app\models\Topic;
use app\models\TopicItem;

/* @var $dataProvider ActiveDataProvider */
/* @var $topicName string */

$this->title = 'Управление разделами: ' . $topicName;
$this->params['breadcrumbs'][] = [
    'label' => 'Управление темами',
    'url' => '/project/' . Yii::$app->request->get('slug') . '/topics',
    'template' => '<li><b class="execute-btn" title="Перейти к списку тем">{link}</b></li>',
];
$this->params['breadcrumbs'][] = $topicName;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if ($dataProvider->totalCount): ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '{begin}-{end}/{totalCount}',
                'columns' => [
                    'name',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return TopicItem::getStatuses()[$model->status];
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-right">{view} {update} {delete}</div>',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<button type="button" class="btn btn-info execute-btn"><i class="fa fa-fw fa-eye"></i></button>',
                                    '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug') .
                                    '/'. $model->slug,
                                    ['title' => 'Смотреть']);
                            },
                            'update' => function ($url, $model) {
                                return (PermissionHelper::can('updateTopic') || ArticleHelper::authorPermission($model->author_id))
                                    ? Html::a('<button type="button" class="btn btn-yellow execute-btn"><i class="fa fa-fw fa-pencil"></i></button>',
                                        '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug') .
                                        '/'. $model->slug . '/update',
                                        ['title' => 'Редактировать'])
                                    : '';
                            },
                            'delete' => function ($url, $model) {
                                return (PermissionHelper::can('deleteTopic') || ArticleHelper::authorPermission($model->author_id))
                                    ? Html::a('<button type="button" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>',
                                        '/project/' . Yii::$app->request->get('slug') . '/' .
                                        Yii::$app->request->get('topicSlug') . '/'. $model->slug . '/delete',
                                        ['onclick' => 'return confirmDelete("раздел");', 'title' => 'Удалить'])
                                    : '';
                            }
                        ]
                    ]
                ]
            ]); ?>
        <?php else: ?>
            <div class="inline-block">Разделов пока нет.</div>
        <?php endif; ?>

        <div class="inline-block float-right">
            <?php if (PermissionHelper::can('createTopicItem') || ArticleHelper::authorPermission(Topic::findModel()->author_id)): ?>
                <?= Html::a('Добавить раздел',
                    '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug') . '/topic-item/create',
                    ['type' => 'button', 'class' => 'btn btn-primary execute-btn']
                ) ?>
            <?php endif; ?>
        </div>
    </div>
</div>