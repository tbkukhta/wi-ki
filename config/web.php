<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'name' => 'Wi-Ki',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'J7XLNa41BQrsflxYmap5qOMWE_seWRM6',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            //'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'errors/error',
            'maxSourceLines' => 20,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['user', 'moder', 'admin'],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                /**
                 * Auth controller
                 */
                '/<action:(login|logout)>' => 'auth/<action>',
                /**
                 * Projects controller
                 */
                '/' => 'projects/index',
                /**
                 * Profile controller
                 */
                '/profile' => 'profile/view',
                '/profile/settings' => 'profile/settings-view',
                /**
                 * Tag controller
                 */
                '/tags' => 'tag/index',
                '/tag/create' => 'tag/create',
                '/tag/<action:(update|delete)>/<id>' => 'tag/<action>',
                /**
                 * User controller
                 */
                '/users' => 'user/index',
                '/user/create' => 'user/create',
                '/user/<action:(update|delete)>/<id>' => 'user/<action>',
                /**
                 * Comment controller
                 */
                '/comment/delete/<id>' => 'comment/delete',
                /**
                 * Project controller
                 */
                '/project/validate-form' => 'project/validate-form',
                '/project/create' => 'project/create',
                '/project/<slug>' => 'project/view',
                '/project/<slug>/<action:(update|delete)>' => 'project/<action>',
                /**
                 * Topic controller
                 */
                '/project/<slug>/topics' => 'topic/index',
                '/project/<slug>/topic/create' => 'topic/create',
                '/project/<slug>/<topicSlug>' => 'topic/view',
                '/project/<slug>/<topicSlug>/<action:(update|delete)>' => 'topic/<action>',
                /**
                 * TopicItem controller
                 */
                '/project/<slug>/<topicSlug>/topic-items' => 'topic-item/index',
                '/project/<slug>/<topicSlug>/topic-item/create' => 'topic-item/create',
                '/project/<slug>/<topicSlug>/<topicItemSlug>' => 'topic-item/view',
                '/project/<slug>/<topicSlug>/<topicItemSlug>/<action:(update|delete)>' => 'topic-item/<action>',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => 'js',
                    'js' => [
                        'jquery/jquery-2.1.3.min.js',
                    ],
                ],
            ],
            'linkAssets' => YII_ENV_DEV,
            'appendTimestamp' => YII_ENV_DEV,
        ],
    ],
    'on beforeRequest' => function () {
        $pathInfo = \Yii::$app->request->pathInfo;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            \Yii::$app->response->redirect('/' . substr(rtrim($pathInfo), 0, -1), 301)->send();
        }
    },
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;