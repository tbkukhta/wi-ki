<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Class BaseController
 * All controllers must extend from this controller
 * @package app\controllers
 */
class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return Yii::$app->response->redirect('/login');
                },
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
}