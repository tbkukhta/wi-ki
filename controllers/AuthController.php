<?php

namespace app\controllers;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\models\LoginForm;
use app\services\PermissionService;

class AuthController extends BaseController
{
    /**
     * Login
     *
     * @return array|string|Response
     */
    public function actionLogin()
    {
        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();

        if (Yii::$app->request->isAjax && $loginForm->load(Yii::$app->request->post())) {
            if ($loginForm->login()) {
                PermissionService::execute();
                return $this->goBack();
            } else {
                return $this->asJson(ActiveForm::validate($loginForm));
            }
        }

        return $this->render('login', [
            'loginForm' => $loginForm,
        ]);
    }

    /**
     * Logout
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}