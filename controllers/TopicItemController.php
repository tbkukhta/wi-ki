<?php

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Topic;
use app\models\TopicItem;
use app\models\Comment;
use yii\data\ActiveDataProvider;
use app\services\PermissionService;
use app\helpers\ArticleHelper;
use app\helpers\PermissionHelper;

class TopicItemController extends BaseController
{
    /**
     * List all topic_items
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionIndex()
    {
        try {
            $topic = Topic::findModel();

            if (!ArticleHelper::authorPermission($topic->author_id)) {
                PermissionService::canGroup(TopicItem::MODEL_NAME);
            }

            $query = TopicItem::find()->where(['topic_id' => $topic->id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['paginationPageSize']
                ]
            ]);

            return $this->render('index', [
                'topicName' => $topic->name,
                'dataProvider' => $dataProvider
            ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Display topic_item page
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        try {
            $model = TopicItem::findModel();
            $topicUrl = '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug');
            $modelName = TopicItem::MODEL_NAME;
            $userPermission = PermissionHelper::canGroup($modelName) || ArticleHelper::authorPermission($model->author_id);
            $comment = new Comment([
                'article_model' => $modelName,
                'article_id' => $model->id,
            ]);
            $breadcrumbs = [
                [
                    'label' => $model->topic->name,
                    'url' => $topicUrl,
                    'template' => '<li><b class="execute-btn" title="Перейти к теме">{link}</b></li>',
                ],
                $model->name
            ];

            return $this->render('/article/view', [
                'model' => $model,
                'modelUrl' => $topicUrl . '/' . $model->slug,
                'modelName' => $modelName,
                'userPermission' => $userPermission,
                'comment' => $comment,
                'breadcrumbs' => $breadcrumbs
            ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Create topic_item
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionCreate()
    {
        try {
            $model = new TopicItem();
            $topic = Topic::findModel();
            $topicUrl = '/project/' . Yii::$app->request->get('slug') . '/' . $topic->slug;

            if (!ArticleHelper::authorPermission($topic->author_id)) {
                PermissionService::canGroup(TopicItem::MODEL_NAME);
            }

            if (Yii::$app->request->isPost) {
                $model->project_id = $topic->project_id;
                $model->topic_id = $topic->id;
                $model->author_id = Yii::$app->user->getId();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect($topicUrl . '/' . $model->slug);
                }
            }

            $title = 'Добавить раздел в тему';
            $breadcrumbs = [
                [
                    'label' => $topic->name,
                    'url' => $topicUrl,
                    'template' => '<li><b class="execute-btn" title="Перейти к теме">{link}</b></li>',
                ],
                $title
            ];
            return $this->render('/article/create', [
                'model' => $model,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Update topic_item
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate()
    {
        try {
            $model = TopicItem::findModel();
            $topicUrl = '/project/' . Yii::$app->request->get('slug') . '/' . Yii::$app->request->get('topicSlug');

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(TopicItem::MODEL_NAME);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect($topicUrl . '/' . $model->slug);
            }

            $title = 'Редактировать раздел';
            $breadcrumbs = [
                [
                    'label' => $model->topic->name,
                    'url' => $topicUrl,
                    'template' => '<li><b class="execute-btn" title="Перейти к теме">{link}</b></li>',
                ],
                [
                    'label' => $model->name,
                    'url' => $topicUrl . '/' . $model->slug,
                    'template' => '<li><b class="execute-btn" title="Перейти к разделу">{link}</b></li>',
                ],
                $title
            ];
            return $this->render('/article/update', [
                'model' => $model,
                'title' => $title,
                'breadcrumbs' => $breadcrumbs
            ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Delete topic_item
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDelete()
    {
        try {
            $model = TopicItem::findModel();

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(TopicItem::MODEL_NAME);
            }

            $model->delete();
            return $this->redirect('/project/' .
                Yii::$app->request->get('slug') . '/' .
                Yii::$app->request->get('topicSlug') .
                '/topic-items');
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}