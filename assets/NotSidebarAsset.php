<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for pages without sidebar
 */
class NotSidebarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap/bootstrap-clearmin.min.css',
        'css/font-awesome.min.css',
        'css/roboto.css',
        'css/material-design.css',
        'css/small-n-flat.css',
        'css/global.css',
        'css/project.css'
    ];

    public $js = [
        'js/bootstrap/bootstrap.min.js',
        'js/common.js',
        'js/notsidebar.js',
    ];

    public $depends = [
        'yii\web\YiiAsset'
    ];
}