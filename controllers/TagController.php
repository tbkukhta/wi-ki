<?php

namespace app\controllers;

use Exception;
use Yii;
use app\models\Tag;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\services\PermissionService;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends BaseController
{
    public $layout = 'main-not-sidebar';

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function beforeAction($action)
    {
        PermissionService::canGroup('tag');

        return parent::beforeAction($action);
    }

    /**
     * Lists all Tag models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Tag::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationPageSize'],
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['/tags']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/tags']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->redirect(['/tags']);
    }


    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}