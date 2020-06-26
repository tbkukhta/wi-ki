<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\Topic;
use app\models\Comment;
use app\services\PermissionService;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\helpers\ArticleHelper;
use app\helpers\PermissionHelper;

class TopicController extends BaseController
{
    /**
     * List all topics
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionIndex()
    {
        try {
            $project = Project::findModel();

            if (!ArticleHelper::authorPermission($project->author_id)) {
                PermissionService::canGroup(Topic::MODEL_NAME);
            }

            $query = Topic::find()->where(['project_id' => $project->id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['paginationPageSize']
                ]
            ]);

            return $this->render('index', ['dataProvider' => $dataProvider]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Display topic page
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        try {
            $model = Topic::findModel();
            $modelUrl = '/project/' . Yii::$app->request->get('slug') . '/' . $model->slug;
            $modelName = Topic::MODEL_NAME;
            $userPermission = PermissionHelper::canGroup($modelName) || ArticleHelper::authorPermission($model->author_id);
            $comment = new Comment([
                'article_model' => $modelName,
                'article_id' => $model->id,
            ]);
            $breadcrumbs = [
                $model->name
            ];

            return $this->render('/article/view', [
                'model' => $model,
                'modelUrl' => $modelUrl,
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
     * Create topic
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionCreate()
    {
        try {
            $model = new Topic();
            $project = Project::findModel();

            if (!ArticleHelper::authorPermission($project->author_id)) {
                PermissionService::canGroup(Topic::MODEL_NAME);
            }

            if (Yii::$app->request->isPost) {
                $model->project_id = $project->id;
                $model->author_id = Yii::$app->user->getId();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect('/project/' . Yii::$app->request->get('slug') . '/' . $model->slug);
                }
            }

            $title = 'Добавить новую тему';
            $breadcrumbs = [$title];
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
     * Update topic
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate()
    {
        try {
            $model = Topic::findModel();
            $projectUrl = '/project/' . Yii::$app->request->get('slug');

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(Topic::MODEL_NAME);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect($projectUrl . '/' . $model->slug);
            }

            $title = 'Редактировать тему';
            $breadcrumbs = [
                [
                    'label' => $model->name,
                    'url' => $projectUrl . '/' . $model->slug,
                    'template' => '<li><b class="execute-btn" title="Перейти к теме">{link}</b></li>',
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
     * Delete topic
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDelete()
    {
        try {
            $model = Topic::findModel();

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(Topic::MODEL_NAME);
            }

            if (!empty($model->topicItems)) {
                Yii::$app->session->setFlash('error', 'Невозможно удалить тему, так как в ней присутствуют разделы.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $model->delete();
            return $this->redirect('/project/' . Yii::$app->request->get('slug') . '/topics');
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}