<?php

namespace app\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use alexantr\elfinder\TinyMCEAction;
use Yii;
use yii\web\Controller;

class ElfinderController extends Controller
{
    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::class,
                'options' => [
                    'roots' => [
                        [
                            'driver' => 'LocalFileSystem',
                            'path' => Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads/elfinder',
                            'URL' => Yii::getAlias('@web') . '/uploads/elfinder',
                            'mimeDetect' => 'internal',
                            'imgLib' => 'gd',
                            'accessControl' => function ($attr, $path) {
                                // hide files/folders which begins with dot
                                return (strpos(basename($path), '.') === 0) ?
                                    !($attr == 'read' || $attr == 'write') :
                                    null;
                            },
                        ],
                    ],
                ],
            ],
            'input' => [
                'class' => InputFileAction::class,
                'connectorRoute' => 'connector',
            ],
            'ckeditor' => [
                'class' => CKEditorAction::class,
                'connectorRoute' => 'connector',
            ],
            'tinymce' => [
                'class' => TinyMCEAction::class,
                'connectorRoute' => 'connector',
            ],
        ];
    }
}