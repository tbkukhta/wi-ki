<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $dataProvider ActiveDataProvider */

$this->title = $this->params['breadcrumbs'][] = 'Управление тегами';

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if ($dataProvider->totalCount): ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '{begin}-{end}/{totalCount}',
                'columns' => [
                    'name', [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '<div class="text-right">{update} {delete}</div>',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<button type="button" class="btn btn-default execute-btn"><i class="fa fa-fw fa-pencil"></i></button>',
                                    '/tag/update/' . $model->id,
                                    ['title' => 'Редактировать']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<button type="button" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>',
                                    '/tag/delete/' . $model->id,
                                    ['onclick' => 'return confirmDelete("тэг");', 'title' => 'Удалить']);
                            }
                        ]
                    ]
                ]
            ]); ?>
        <?php else: ?>
            <div class="inline-block">Тегов пока нет.</div>
        <?php endif; ?>

        <div class="inline-block float-right">
            <?= Html::a('Добавить тег', '/tag/create', ['type' => 'button', 'class' => 'btn btn-primary execute-btn']) ?>
        </div>
    </div>
</div>