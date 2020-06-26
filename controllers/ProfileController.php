<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProfileController extends BaseController
{
    public $layout = 'main-not-sidebar';

    /**
     * Display user profile
     *
     * @return string
     */
    public function actionView()
    {
        return $this->render('view');
    }

    /**
     * Display user profile settings
     *
     * @return string|\yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionSettingsView()
    {
        $user = User::findOne(Yii::$app->user->identity->getId());

        if ($user === null) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        if (Yii::$app->request->isPost) {
            if ($user->load(Yii::$app->request->post()) && $user->save()) {
                return Yii::$app->response->redirect(Url::toRoute('/profile'));
            }
        }

        return $this->render('settings-view', ['user' => $user]);
    }
}