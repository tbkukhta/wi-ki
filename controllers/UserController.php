<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\base\Action;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\models\ChangePasswordForm;
use yii\web\NotFoundHttpException;
use app\services\PermissionService;

class UserController extends BaseController
{
    public $layout = 'main-not-sidebar';

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function beforeAction($action)
    {
        PermissionService::canGroup('user');

        return parent::beforeAction($action);
    }

    /**
     * Displays users page
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['paginationPageSize']
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates user with default password
     *
     * @return string|\yii\console\Response|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $user = new User();

        if (Yii::$app->request->isPost) {
            if ($user->load(Yii::$app->request->post()) && $user->create()) {
                return Yii::$app->response->redirect('/users');
            }
        }

        return $this->render('create', [
            'model' => $user,
        ]);
    }

    /**
     * Updates user data
     *
     * @param $id
     * @return string|\yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $user = $this->findUser($id);

        if (Yii::$app->request->isPost) {
            if ($user->load(Yii::$app->request->post()) && $user->save()) {
                return Yii::$app->response->redirect('/users');
            }
        }

        return $this->render('update', [
            'model' => $user,
        ]);
    }

    /**
     * Deletes user
     *
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = $this->findUser($id);
        $login = $user->login;

        if ($login === Yii::$app->params['adminLogin'] || $login === Yii::$app->user->identity->login) {
            Yii::$app->session->setFlash('error', 'Невозможно удалить пользователя.');
        } else {
            $user->delete();
        }

        return $this->redirect('/users');
    }

    /**
     * Save the password if it is valid
     * Return validation errors otherwise
     *
     * @return array|bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionChangePassword()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $changePasswordForm = new ChangePasswordForm(Yii::$app->user->id);

            if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->savePassword()) {
                return false;
            }

            $response = [];
            foreach ($changePasswordForm->getErrors() as $attribute => $errors) {
                $response[Html::getInputId($changePasswordForm, $attribute)] = $errors;
            }
            return $response;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}