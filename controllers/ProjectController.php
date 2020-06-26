<?php

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Project;
use app\models\Comment;
use yii\helpers\Url;
use app\services\PermissionService;
use app\helpers\ArticleHelper;
use app\helpers\PermissionHelper;

class ProjectController extends BaseController
{
    /**
     * Display project page
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        try {
            $model = Project::findModel();
            $modelUrl = '/project/' . $model->slug;
            $modelName = Project::MODEL_NAME;
            $userPermission = PermissionHelper::canGroup($modelName) || ArticleHelper::authorPermission($model->author_id);
            $comment = new Comment([
                'article_model' => $modelName,
                'article_id' => $model->id,
            ]);

            return $this->render('/article/view', [
                'model' => $model,
                'modelUrl' => $modelUrl,
                'modelName' => $modelName,
                'userPermission' => $userPermission,
                'comment' => $comment
            ]);
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Create project
     * Return validation errors otherwise
     *
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Project();
            $model->author_id = Yii::$app->user->getId();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(Url::toRoute('/'));
            }

            $response = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                $response[Html::getInputId($model, $attribute)] = $errors;
            }
            return $this->asJson($response);
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Update project
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate()
    {
        try {
            $model = Project::findModel();

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(Project::MODEL_NAME);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect('/project/' . $model->slug);
            }

            $title = 'Редактировать проект';
            $breadcrumbs = [$title];
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
     * Delete project
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDelete()
    {
        try {
            $model = Project::findModel();

            if (!ArticleHelper::authorPermission($model->author_id)) {
                PermissionService::canGroup(Project::MODEL_NAME);
            }

            if (!empty($model->topics)) {
                Yii::$app->session->setFlash('error', 'Невозможно удалить проект, так как в нём присутствуют темы.');
                return $this->redirect('/project/' . Yii::$app->request->get('slug') . '/update');
            }

            $model->delete();
            return $this->redirect('/');
        } catch (Exception $e) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}